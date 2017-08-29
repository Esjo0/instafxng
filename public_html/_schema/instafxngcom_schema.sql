-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2017 at 10:34 AM
-- Server version: 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tboy9_turbo`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_client`
--

DROP TABLE IF EXISTS `active_client`;
CREATE TABLE IF NOT EXISTS `active_client` (
  `active_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` int(11) NOT NULL,
  `accounts` int(11) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`active_client_id`),
  UNIQUE KEY `date_UNIQUE` (`date`),
  UNIQUE KEY `active_client_id_UNIQUE` (`active_client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass_salt` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive\n3 - Suspended',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_code_UNIQUE` (`admin_code`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `salt_UNIQUE` (`pass_salt`),
  UNIQUE KEY `admin_id_UNIQUE` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_bulletin`
--

DROP TABLE IF EXISTS `admin_bulletin`;
CREATE TABLE IF NOT EXISTS `admin_bulletin` (
  `admin_bulletin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `allowed_admin` text NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 - Published\n2 - Draft\n3 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_bulletin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_privilege`
--

DROP TABLE IF EXISTS `admin_privilege`;
CREATE TABLE IF NOT EXISTS `admin_privilege` (
  `admin_privilege_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `allowed_pages` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_privilege_id`,`admin_code`),
  UNIQUE KEY `admin_privilege_id_UNIQUE` (`admin_privilege_id`),
  UNIQUE KEY `admin_code_UNIQUE` (`admin_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `display_image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 - Published\n2 - Draft\n3 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_id_UNIQUE` (`article_id`),
  KEY `fk_article_admin1_idx` (`admin_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(150) NOT NULL,
  `bank_alias` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  UNIQUE KEY `banks_id_UNIQUE` (`bank_id`),
  UNIQUE KEY `bank_name_UNIQUE` (`bank_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_category`
--

DROP TABLE IF EXISTS `campaign_category`;
CREATE TABLE IF NOT EXISTS `campaign_category` (
  `campaign_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `client_group` enum('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL COMMENT '1 - All Clients 2 - Last Month New Clients 3 - Free Training Campaign Clients 4 - Level 1 Clients 5 - Level 2 Clients 6 - Level 3 Clients 7 - Unverified Clients 8 - Dinner Clients 9 - Lagos Clients 10 - Online Training Students 11 - Lekki Students 12 - Diamond Students',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_category_id`),
  UNIQUE KEY `campaign_category_id_UNIQUE` (`campaign_category_id`),
  UNIQUE KEY `client_group_UNIQUE` (`client_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_email`
--

DROP TABLE IF EXISTS `campaign_email`;
CREATE TABLE IF NOT EXISTS `campaign_email` (
  `campaign_email_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `campaign_category_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `send_status` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `status` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Approved\n4 - Disapproved\n5 - In Progress\n6 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_email_id`),
  UNIQUE KEY `campaign_email_id_UNIQUE` (`campaign_email_id`),
  KEY `fk_campaign_email_campaign_category1_idx` (`campaign_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_email_solo`
--

DROP TABLE IF EXISTS `campaign_email_solo`;
CREATE TABLE IF NOT EXISTS `campaign_email_solo` (
  `campaign_email_solo_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `solo_group` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Inactive',
  `day_to_send` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_email_solo_id`),
  UNIQUE KEY `campaign_email_solo_id_UNIQUE` (`campaign_email_solo_id`),
  KEY `fk_campaign_email_solo_campaign_email_solo_group1_idx` (`solo_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_email_solo_group`
--

DROP TABLE IF EXISTS `campaign_email_solo_group`;
CREATE TABLE IF NOT EXISTS `campaign_email_solo_group` (
  `campaign_email_solo_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`campaign_email_solo_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_email_track`
--

DROP TABLE IF EXISTS `campaign_email_track`;
CREATE TABLE IF NOT EXISTS `campaign_email_track` (
  `campaign_track_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `recipient_query` text NOT NULL,
  `total_recipient` int(11) NOT NULL,
  `current_offset` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_track_id`),
  UNIQUE KEY `campaign_id_UNIQUE` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_sms`
--

DROP TABLE IF EXISTS `campaign_sms`;
CREATE TABLE IF NOT EXISTS `campaign_sms` (
  `campaign_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `campaign_category_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `send_status` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `status` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Approved\n4 - Disapproved\n5 - In Progress\n6 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_sms_id`),
  UNIQUE KEY `campaign_sms_id_UNIQUE` (`campaign_sms_id`),
  KEY `fk_campaign_sms_campaign_category1_idx` (`campaign_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_sms_track`
--

DROP TABLE IF EXISTS `campaign_sms_track`;
CREATE TABLE IF NOT EXISTS `campaign_sms_track` (
  `campaign_track_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `recipient_query` text NOT NULL,
  `total_recipient` int(11) NOT NULL,
  `current_offset` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_track_id`),
  UNIQUE KEY `campaign_id_UNIQUE` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `career_jobs`
--

DROP TABLE IF EXISTS `career_jobs`;
CREATE TABLE IF NOT EXISTS `career_jobs` (
  `career_jobs_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `job_code` varchar(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Closed\n2 - Open',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`career_jobs_id`),
  UNIQUE KEY `career_jobs_id_UNIQUE` (`career_jobs_id`),
  UNIQUE KEY `job_code_UNIQUE` (`job_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(1) DEFAULT '1',
  `article_id` int(11) DEFAULT NULL,
  `comment` varchar(10000) DEFAULT NULL,
  `reply_to` int(11) DEFAULT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'OFF',
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `table_name_id_uindex` (`comment_id`),
  UNIQUE KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(30) NOT NULL,
  `capital` varchar(20) NOT NULL,
  `abbr` varchar(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `state_id_UNIQUE` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  UNIQUE KEY `currency_id_UNIQUE` (`currency_id`),
  UNIQUE KEY `symbol_UNIQUE` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='This table holds info about people that are not yet clients.';

-- --------------------------------------------------------

--
-- Table structure for table `customer_care_log`
--

DROP TABLE IF EXISTS `customer_care_log`;
CREATE TABLE IF NOT EXISTS `customer_care_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_desc` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING',
  `admin_code` varchar(255) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='This table contains both complaints and feedback.';

-- --------------------------------------------------------

--
-- Table structure for table `deposit_comment`
--

DROP TABLE IF EXISTS `deposit_comment`;
CREATE TABLE IF NOT EXISTS `deposit_comment` (
  `deposit_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` varchar(15) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL COMMENT 'For comments entered by Admin\nAlso for system comments, e.g. Admin change to confirmed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`deposit_comment_id`),
  UNIQUE KEY `deposit_comment_id_UNIQUE` (`deposit_comment_id`),
  KEY `fk_deposit_comment_user_deposit1_idx` (`trans_id`),
  KEY `fk_deposit_comment_admin1_idx` (`admin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_monitoring`
--

DROP TABLE IF EXISTS `deposit_monitoring`;
CREATE TABLE IF NOT EXISTS `deposit_monitoring` (
  `deposit_monitoring_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_monitoring_id`),
  UNIQUE KEY `transaction_monitoring_id_UNIQUE` (`deposit_monitoring_id`),
  KEY `fk_transaction_monitoring_admin1_idx` (`admin_code`),
  KEY `fk_deposit_user_deposit1` (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dinner2016_comment`
--

DROP TABLE IF EXISTS `dinner2016_comment`;
CREATE TABLE IF NOT EXISTS `dinner2016_comment` (
  `dinner2016_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `dinner_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`dinner2016_comment_id`),
  KEY `fk_dinner2016_comment_dinner_20161_idx` (`dinner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dinner_2016`
--

DROP TABLE IF EXISTS `dinner_2016`;
CREATE TABLE IF NOT EXISTS `dinner_2016` (
  `id_dinner_2016` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `invite` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - early 2 - late',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id_dinner_2016`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_dinner_2016`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edu_course`
--

DROP TABLE IF EXISTS `edu_course`;
CREATE TABLE IF NOT EXISTS `edu_course` (
  `edu_course_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `course_code` varchar(5) NOT NULL,
  `course_order` int(11) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `difficulty_level` enum('1','2','3','4') NOT NULL COMMENT '1 - Beginner\n2 - Intermediate\n3 - Advanced\n4 - Expert',
  `display_image` varchar(255) DEFAULT NULL,
  `intro_video_url` varchar(20) DEFAULT NULL,
  `time_required` varchar(45) DEFAULT NULL,
  `course_fee` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Free\n2 - Paid',
  `course_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` datetime NOT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`edu_course_id`),
  UNIQUE KEY `code_UNIQUE` (`course_code`),
  UNIQUE KEY `edu_course_id_UNIQUE` (`edu_course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edu_lesson`
--

DROP TABLE IF EXISTS `edu_lesson`;
CREATE TABLE IF NOT EXISTS `edu_lesson` (
  `edu_lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_order` int(11) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `time_required` varchar(45) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`edu_lesson_id`),
  UNIQUE KEY `edu_lesson_id_UNIQUE` (`edu_lesson_id`),
  KEY `fk_edu_lesson_edu_course1_idx` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edu_lesson_exercise`
--

DROP TABLE IF EXISTS `edu_lesson_exercise`;
CREATE TABLE IF NOT EXISTS `edu_lesson_exercise` (
  `edu_lesson_exercise_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text,
  `option_d` text,
  `right_option` enum('A','B','C','D') NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`edu_lesson_exercise_id`),
  UNIQUE KEY `edu_lesson_exercise_id_UNIQUE` (`edu_lesson_exercise_id`),
  KEY `fk_edu_lesson_exercise_edu_lesson1_idx` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_reg`
--

DROP TABLE IF EXISTS `event_reg`;
CREATE TABLE IF NOT EXISTS `event_reg` (
  `event_reg_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email_address` varchar(45) NOT NULL,
  `ifx_acct_no` varchar(11) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`event_reg_id`),
  UNIQUE KEY `event_reg_id_UNIQUE` (`event_reg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rate_log`
--

DROP TABLE IF EXISTS `exchange_rate_log`;
CREATE TABLE IF NOT EXISTS `exchange_rate_log` (
  `exchange_rate_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `change_date` date NOT NULL,
  `deposit_ilpr` decimal(10,2) NOT NULL,
  `deposit_nonilpr` decimal(10,2) NOT NULL,
  `withdraw` decimal(10,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`exchange_rate_log_id`),
  UNIQUE KEY `exchange_rate_log_id_UNIQUE` (`exchange_rate_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facility_category`
--

DROP TABLE IF EXISTS `facility_category`;
CREATE TABLE IF NOT EXISTS `facility_category` (
  `facility_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`facility_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facility_inventory`
--

DROP TABLE IF EXISTS `facility_inventory`;
CREATE TABLE IF NOT EXISTS `facility_inventory` (
  `facility_inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `admin_code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `purchase_date` datetime NOT NULL,
  `remark` text,
  `inventory_date` timestamp NULL DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Good Condition\n2 - Bad Condition\n3 - Write off',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`facility_inventory_id`),
  KEY `fk_facility_inventory_facility_station1_idx` (`station_id`),
  KEY `fk_facility_inventory_facility_category1_idx` (`category_id`),
  KEY `fk_facility_inventory_admin1_idx` (`admin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facility_request`
--

DROP TABLE IF EXISTS `facility_request`;
CREATE TABLE IF NOT EXISTS `facility_request` (
  `facility_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(5) NOT NULL,
  `station_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Confirmed\n3 - Confirmation Declined\n4 - Approved\n5 - Approval Declined\n6 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`facility_request_id`),
  KEY `fk_facility_request_facility_station1_idx` (`station_id`),
  KEY `fk_facility_request_admin1_idx` (`author`),
  KEY `fk_facility_request_facility_category1_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facility_request_comment`
--

DROP TABLE IF EXISTS `facility_request_comment`;
CREATE TABLE IF NOT EXISTS `facility_request_comment` (
  `facility_request_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `request_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`facility_request_comment_id`),
  KEY `fk_facility_request_comment_admin1_idx` (`admin_code`),
  KEY `fk_facility_request_comment_facility_request1_idx` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facility_station`
--

DROP TABLE IF EXISTS `facility_station`;
CREATE TABLE IF NOT EXISTS `facility_station` (
  `facility_station_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`facility_station_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forum_participant`
--

DROP TABLE IF EXISTS `forum_participant`;
CREATE TABLE IF NOT EXISTS `forum_participant` (
  `forum_participant_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `middle_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8 NOT NULL,
  `venue` enum('1','2') CHARACTER SET utf8 NOT NULL COMMENT '1 - Diamond Estate\n2 - Eastline Complex',
  `forum_activate` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`forum_participant_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_registrations_archive`
--

DROP TABLE IF EXISTS `forum_registrations_archive`;
CREATE TABLE IF NOT EXISTS `forum_registrations_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8 NOT NULL,
  `venue` varchar(45) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  `comment` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_registrations_archive2`
--

DROP TABLE IF EXISTS `forum_registrations_archive2`;
CREATE TABLE IF NOT EXISTS `forum_registrations_archive2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8 NOT NULL,
  `venue` varchar(45) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  `comment` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `free_training_campaign`
--

DROP TABLE IF EXISTS `free_training_campaign`;
CREATE TABLE IF NOT EXISTS `free_training_campaign` (
  `free_training_campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `training_interest` enum('1','2') DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `training_centre` enum('1','2','3') DEFAULT NULL COMMENT '1 - Diamond Estate2 - Ikota Office3 - Online',
  `attendant` enum('1','2') NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`free_training_campaign_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `phone_UNIQUE` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `free_training_campaign_comment`
--

DROP TABLE IF EXISTS `free_training_campaign_comment`;
CREATE TABLE IF NOT EXISTS `free_training_campaign_comment` (
  `free_training_campaign_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `training_campaign_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`free_training_campaign_comment_id`),
  UNIQUE KEY `free_training_campaign_comment_id_UNIQUE` (`free_training_campaign_comment_id`),
  KEY `fk_free_training_campaign_comment_free_training_campaign1_idx` (`training_campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lekki_office_training`
--

DROP TABLE IF EXISTS `lekki_office_training`;
CREATE TABLE IF NOT EXISTS `lekki_office_training` (
  `id_new_office_warming` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id_new_office_warming`),
  UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_new_office_warming`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_of_dates`
--

DROP TABLE IF EXISTS `log_of_dates`;
CREATE TABLE IF NOT EXISTS `log_of_dates` (
  `log_of_dates_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_of_day` date NOT NULL,
  PRIMARY KEY (`log_of_dates_id`),
  UNIQUE KEY `log_of_dates_id_UNIQUE` (`log_of_dates_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `miss_tourism_lagos`
--

DROP TABLE IF EXISTS `miss_tourism_lagos`;
CREATE TABLE IF NOT EXISTS `miss_tourism_lagos` (
  `miss_tourism_lagos_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL,
  `age` int(2) NOT NULL,
  `school` varchar(255) NOT NULL,
  `height` varchar(4) NOT NULL,
  `hobby` varchar(255) DEFAULT NULL,
  `fav_food` varchar(255) DEFAULT NULL,
  `ambition` text,
  `contest_id` int(2) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_count` int(2) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`miss_tourism_lagos_id`),
  UNIQUE KEY `contest_id_UNIQUE` (`contest_id`),
  UNIQUE KEY `miss_tourism_lagos_id_UNIQUE` (`miss_tourism_lagos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `new_office_warming`
--

DROP TABLE IF EXISTS `new_office_warming`;
CREATE TABLE IF NOT EXISTS `new_office_warming` (
  `id_new_office_warming` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id_new_office_warming`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_new_office_warming`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
CREATE TABLE IF NOT EXISTS `partner` (
  `partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(5) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `settlement_ifxaccount_id` int(11) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Active\n3 - Inactive\n4 - Suspended',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`partner_id`),
  UNIQUE KEY `partner_code_UNIQUE` (`partner_code`),
  UNIQUE KEY `partners_id_UNIQUE` (`partner_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  KEY `fk_partner_user_ifxaccount1_idx` (`settlement_ifxaccount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_balance`
--

DROP TABLE IF EXISTS `partner_balance`;
CREATE TABLE IF NOT EXISTS `partner_balance` (
  `partner_balance_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(5) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Trading\n2 - Financial',
  `balance` decimal(10,2) NOT NULL,
  `createad` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`partner_balance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_financial_activity_commission`
--

DROP TABLE IF EXISTS `partner_financial_activity_commission`;
CREATE TABLE IF NOT EXISTS `partner_financial_activity_commission` (
  `partner_financial_activity_commission_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(5) NOT NULL,
  `reference_trans_id` varchar(15) NOT NULL COMMENT 'Gets input from the trans_id column of the following tables\n\nuser_deposit\nuser_withdrawal\npartner_payment',
  `amount` decimal(10,2) NOT NULL,
  `trans_type` enum('1','2','3') NOT NULL COMMENT '1 - Credit On User_deposit \n2 - Credit On User_withdrawal \n3 - Debit',
  `balance` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`partner_financial_activity_commission_id`),
  UNIQUE KEY `partner_financial_activity_commission_id_UNIQUE` (`partner_financial_activity_commission_id`),
  KEY `fk_partner_financial_activity_commission_partner1_idx` (`partner_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_payment`
--

DROP TABLE IF EXISTS `partner_payment`;
CREATE TABLE IF NOT EXISTS `partner_payment` (
  `partner_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(10) DEFAULT NULL,
  `partner_code` varchar(50) DEFAULT NULL,
  `account_id` varchar(30) DEFAULT NULL,
  `amount` double(10,2) DEFAULT NULL,
  `comment` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_trading_commission`
--

DROP TABLE IF EXISTS `partner_trading_commission`;
CREATE TABLE IF NOT EXISTS `partner_trading_commission` (
  `partner_trading_commission_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(5) NOT NULL,
  `reference_trans_id` varchar(15) NOT NULL COMMENT 'Gets input from the trans_id column of the following tables\n\ntrading commission\npartner_payment',
  `amount` decimal(10,2) NOT NULL,
  `trans_type` enum('1','2') NOT NULL COMMENT '1 - Credit for Trading Commission\n2 - Debit',
  `balance` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`partner_trading_commission_id`),
  UNIQUE KEY `partner_payment_id_UNIQUE` (`partner_trading_commission_id`),
  UNIQUE KEY `reference_trans_id_UNIQUE` (`reference_trans_id`),
  KEY `fk_partner_payment_trading_commission_partner1_idx` (`partner_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_withdrawal`
--

DROP TABLE IF EXISTS `partner_withdrawal`;
CREATE TABLE IF NOT EXISTS `partner_withdrawal` (
  `partner_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(15) NOT NULL,
  `admin_code` varchar(5) DEFAULT NULL,
  `partner_code` varchar(5) NOT NULL,
  `account_id` int(11) NOT NULL COMMENT 'ifxacccount_id, bank_account_id',
  `amount` decimal(10,2) NOT NULL,
  `trans_type` enum('1','2') DEFAULT NULL COMMENT '1 - IFX Payment \n2 - Bank Payment',
  `transfer_reference` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `comment` text NOT NULL,
  PRIMARY KEY (`partner_payment_id`),
  UNIQUE KEY `partner_payment_id_UNIQUE` (`partner_payment_id`),
  UNIQUE KEY `transaction_id_UNIQUE` (`transaction_id`),
  KEY `fk_partner_payment_admin1_idx` (`admin_code`),
  KEY `fk_partner_payment_user_ifxaccount1_idx` (`account_id`),
  KEY `fk_partner_payment_partner1_idx` (`partner_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_based_claimed`
--

DROP TABLE IF EXISTS `point_based_claimed`;
CREATE TABLE IF NOT EXISTS `point_based_claimed` (
  `point_based_claimed_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `point_claimed` decimal(10,2) NOT NULL,
  `dollar_amount` decimal(10,2) NOT NULL,
  `rate_used` decimal(10,2) NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Completed\n3 - Failed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_based_claimed_id`),
  UNIQUE KEY `point_based_claimed_id_UNIQUE` (`point_based_claimed_id`),
  KEY `fk_point_based_claimed_user1_idx` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_based_reward`
--

DROP TABLE IF EXISTS `point_based_reward`;
CREATE TABLE IF NOT EXISTS `point_based_reward` (
  `point_based_reward_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `point_earned` decimal(10,2) NOT NULL,
  `rate_used` decimal(10,2) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Deposit\n2 - Trading',
  `reference` int(11) NOT NULL,
  `is_active` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Yes 2 - No',
  `date_earned` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_based_reward_id`),
  KEY `fk_points_based_reward_user1_idx` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_ranking`
--

DROP TABLE IF EXISTS `point_ranking`;
CREATE TABLE IF NOT EXISTS `point_ranking` (
  `point_ranking_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `year_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `year_earned_archive` decimal(10,2) NOT NULL DEFAULT '0.00',
  `year_rank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_earned_archive` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_rank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point_claimed` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_ranking_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_ranking_log`
--

DROP TABLE IF EXISTS `point_ranking_log`;
CREATE TABLE IF NOT EXISTS `point_ranking_log` (
  `point_ranking_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `position` int(2) NOT NULL,
  `point_earned` decimal(10,2) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly\n2 - Yearly',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_ranking_log_id`),
  KEY `fk_point_ranking_log_user1_idx` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_season`
--

DROP TABLE IF EXISTS `point_season`;
CREATE TABLE IF NOT EXISTS `point_season` (
  `point_season_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly 2 - Yearly',
  `is_active` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_season_id`),
  UNIQUE KEY `point_season_id_UNIQUE` (`point_season_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

DROP TABLE IF EXISTS `reminder`;
CREATE TABLE IF NOT EXISTS `reminder` (
  `reminder_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(255) NOT NULL,
  `full_name` varchar(500) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `ifx_acc_no` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `effect_date` varchar(255) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'ON',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reminder_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_contact_comment`
--

DROP TABLE IF EXISTS `sales_contact_comment`;
CREATE TABLE IF NOT EXISTS `sales_contact_comment` (
  `sales_contact_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_contact_email`
--

DROP TABLE IF EXISTS `sales_contact_email`;
CREATE TABLE IF NOT EXISTS `sales_contact_email` (
  `sales_contact_email_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_contact_sms`
--

DROP TABLE IF EXISTS `sales_contact_sms`;
CREATE TABLE IF NOT EXISTS `sales_contact_sms` (
  `sales_contact_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_sms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `signal_daily`
--

DROP TABLE IF EXISTS `signal_daily`;
CREATE TABLE IF NOT EXISTS `signal_daily` (
  `signal_daily_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_id` int(11) NOT NULL,
  `order_type` varchar(500) NOT NULL,
  `price` varchar(500) NOT NULL,
  `take_profit` varchar(500) NOT NULL,
  `stop_loss` varchar(500) NOT NULL,
  `signal_date` varchar(500) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`signal_daily_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `signal_symbol`
--

DROP TABLE IF EXISTS `signal_symbol`;
CREATE TABLE IF NOT EXISTS `signal_symbol` (
  `signal_symbol_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`signal_symbol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `snappy_help`
--

DROP TABLE IF EXISTS `snappy_help`;
CREATE TABLE IF NOT EXISTS `snappy_help` (
  `snappy_help_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `content` text NOT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1 - Active\n2 - Inactive\n3 - Draft',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`snappy_help_id`),
  UNIQUE KEY `snappy_help_id_UNIQUE` (`snappy_help_id`),
  KEY `fk_snappy_help_admin1_idx` (`admin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE IF NOT EXISTS `state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(30) NOT NULL,
  `capital` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `state_id_UNIQUE` (`state_id`),
  KEY `fk_state_country1_idx` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_message`
--

DROP TABLE IF EXISTS `system_message`;
CREATE TABLE IF NOT EXISTS `system_message` (
  `system_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `constant` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `value` text NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Email\n2 - SMS',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`system_message_id`),
  UNIQUE KEY `constant_UNIQUE` (`constant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_setting`
--

DROP TABLE IF EXISTS `system_setting`;
CREATE TABLE IF NOT EXISTS `system_setting` (
  `system_setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `constant` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Rates\n2 - Schedules',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`system_setting_id`),
  UNIQUE KEY `constant_UNIQUE` (`constant`),
  UNIQUE KEY `system_setting_id_UNIQUE` (`system_setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trading_commission`
--

DROP TABLE IF EXISTS `trading_commission`;
CREATE TABLE IF NOT EXISTS `trading_commission` (
  `trading_commission_id` int(11) NOT NULL AUTO_INCREMENT,
  `ifx_acct_no` varchar(11) NOT NULL,
  `volume` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date_earned` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`trading_commission_id`),
  UNIQUE KEY `trading_commission_id_UNIQUE` (`trading_commission_id`),
  KEY `fk_user_trading_commission_currency1_idx` (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `train_questions`
--

DROP TABLE IF EXISTS `train_questions`;
CREATE TABLE IF NOT EXISTS `train_questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `right_option` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`),
  KEY `fk_train_questions_edu_course1_idx` (`course_id`),
  KEY `fk_train_questions_edu_lesson1_idx` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `train_question_options`
--

DROP TABLE IF EXISTS `train_question_options`;
CREATE TABLE IF NOT EXISTS `train_question_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `option` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`option_id`),
  KEY `fk_train_question_options_train_questions1_idx` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass_salt` varchar(64) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `title` varchar(5) DEFAULT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Client\n2 - Partner\n3 - Prospect',
  `campaign_subscribe` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Subscribed\n2 - Not Subscribed\n3 - Cannot Receive Email',
  `referred_by_code` varchar(5) NOT NULL DEFAULT 'BBLR',
  `sales_last_contact` timestamp NULL DEFAULT NULL,
  `sales_next_contact` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Active2 - Inactive3 - Probation4 - Suspended',
  `reset_code` varchar(20) DEFAULT NULL,
  `reset_expiry` timestamp NULL DEFAULT NULL,
  `point_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_account_flag`
--

DROP TABLE IF EXISTS `user_account_flag`;
CREATE TABLE IF NOT EXISTS `user_account_flag` (
  `user_account_flag_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_account_flag_id`),
  UNIQUE KEY `user_account_flag_id_UNIQUE` (`user_account_flag_id`),
  KEY `fk_user_account_flag_user_ifxaccount1_idx` (`ifxaccount_id`),
  KEY `fk_user_account_flag_admin1_idx` (`admin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_bank`
--

DROP TABLE IF EXISTS `user_bank`;
CREATE TABLE IF NOT EXISTS `user_bank` (
  `user_bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `bank_acct_name` varchar(100) NOT NULL,
  `bank_acct_no` varchar(10) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `is_active` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_bank_id`),
  UNIQUE KEY `bank_id_UNIQUE` (`user_bank_id`),
  KEY `fk_bank_user1_idx` (`user_code`),
  KEY `fk_user_bank_banks1_idx` (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_bonus_deposit`
--

DROP TABLE IF EXISTS `user_bonus_deposit`;
CREATE TABLE IF NOT EXISTS `user_bonus_deposit` (
  `user_bonus_deposit_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `user_deposit_id` int(11) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_bonus_deposit_id`),
  UNIQUE KEY `user_deposit_deposit_id_UNIQUE` (`user_deposit_id`),
  KEY `fk_user_bonus_deposit_user_deposit1_idx` (`user_deposit_id`),
  KEY `fk_user_bonus_deposit_user_ifxaccount1` (`ifxaccount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_credential`
--

DROP TABLE IF EXISTS `user_credential`;
CREATE TABLE IF NOT EXISTS `user_credential` (
  `user_credential_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `idcard` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL COMMENT '1 - Passport\n2 - ID Card\n3 - Signature',
  `signature` varchar(255) DEFAULT NULL,
  `doc_status` enum('000','001','010','011','100','101','110','111') DEFAULT '000' COMMENT '000 - None Approved\n001 - Signature Approved\n010 - Passport Approved\n011 - Passport and Signature Approved\n100 - Idcard Approved\n101 - Idcard and Signature Approved\n110 - Idcard and Passport Approved\n111 - All Approved',
  `remark` varchar(255) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation\n2 - Moderated',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_credential_id`),
  UNIQUE KEY `credential_id_UNIQUE` (`user_credential_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  KEY `fk_credential_user1_idx` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_deposit`
--

DROP TABLE IF EXISTS `user_deposit`;
CREATE TABLE IF NOT EXISTS `user_deposit` (
  `user_deposit_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` varchar(15) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `exchange_rate` decimal(10,2) NOT NULL,
  `dollar_ordered` decimal(10,2) NOT NULL,
  `naira_equivalent_dollar_ordered` decimal(10,2) NOT NULL,
  `naira_service_charge` decimal(10,2) NOT NULL,
  `naira_vat_charge` decimal(10,2) NOT NULL,
  `naira_stamp_duty` decimal(10,2) NOT NULL,
  `naira_total_payable` decimal(10,2) NOT NULL,
  `client_naira_notified` decimal(10,2) DEFAULT NULL,
  `client_pay_date` date DEFAULT NULL,
  `client_pay_method` enum('1','2','3','4','5','6','7','8','9') DEFAULT NULL COMMENT '1 - WebPay 2 - Internet Transfer 3 - ATM Transfer 4 - Bank Transfer 5 - Mobile Money Transfer 6 - Cash Deposit 7 - Office Funding 8 - Not Listed 9 - USSD',
  `client_reference` varchar(255) DEFAULT NULL,
  `client_comment` varchar(255) DEFAULT NULL,
  `client_comment_response` enum('1','2') DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `client_notified_date` datetime DEFAULT NULL,
  `real_naira_confirmed` decimal(10,2) DEFAULT NULL,
  `real_dollar_equivalent` decimal(10,2) DEFAULT NULL,
  `points_claimed_id` int(11) DEFAULT NULL,
  `transfer_reference` text COMMENT 'Deposit Transaction Reference Details From Instaforex After Completing The Transaction',
  `deposit_origin` enum('1','2','3') DEFAULT '1' COMMENT '1 - Online\n2 - Diamond Office\n3 - Ikota Office',
  `status` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated\n2 - Notified\n3 - Confirmation In Progress\n4 - Confirmation Declined\n5 - Confirmed\n6 - Funding In Progress\n7 - Funding Declined\n8 - Funded / Completed\n9 - Payment Failed\n10 - Expired',
  `order_complete_time` timestamp NULL DEFAULT NULL COMMENT 'Time order status was changed to complete',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_deposit_id`),
  UNIQUE KEY `trans_id_UNIQUE` (`trans_id`),
  UNIQUE KEY `deposit_id_UNIQUE` (`user_deposit_id`),
  UNIQUE KEY `points_claimed_id_UNIQUE` (`points_claimed_id`),
  KEY `fk_deposit_ifxaccount1_idx` (`ifxaccount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_deposit_meta`
--

DROP TABLE IF EXISTS `user_deposit_meta`;
CREATE TABLE IF NOT EXISTS `user_deposit_meta` (
  `user_deposit_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_deposit_id` int(11) NOT NULL,
  `trans_status_code` varchar(10) NOT NULL,
  `trans_status_message` varchar(255) NOT NULL,
  `trans_amount` decimal(10,2) NOT NULL,
  `trans_currency` varchar(3) NOT NULL DEFAULT '566',
  `gateway_name` varchar(10) NOT NULL,
  `full_verify_hash` varchar(128) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_deposit_meta_id`),
  UNIQUE KEY `iduser_deposit_meta_UNIQUE` (`user_deposit_meta_id`),
  UNIQUE KEY `user_deposit_id_UNIQUE` (`user_deposit_id`),
  KEY `fk_user_deposit_meta_user_deposit1_idx` (`user_deposit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_deposits`
--

DROP TABLE IF EXISTS `user_edu_deposits`;
CREATE TABLE IF NOT EXISTS `user_edu_deposits` (
  `user_edu_deposits_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `course_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stamp_duty` decimal(10,2) DEFAULT NULL,
  `gateway_charge` decimal(10,2) DEFAULT NULL,
  `pay_method` enum('1','2','3','4','5','6','7','8') NOT NULL DEFAULT '1' COMMENT '1 - WebPay\n2 - Internet Transfer\n3 - ATM Transfer\n4 - Bank Transfer\n5 - Mobile Money Transfer\n6 - Cash Deposit\n7 - Office Payment\n8 - Not Listed',
  `deposit_origin` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Online\n2 - Diamond Office\n3 - Eastline Office',
  `status` enum('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated\n2 - Notified\n3 - Confirmed\n4 - Declined\n5 - Failed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_deposits_id`),
  UNIQUE KEY `user_edu_deposits_id_UNIQUE` (`user_edu_deposits_id`),
  UNIQUE KEY `trans_id_UNIQUE` (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_deposits_comment`
--

DROP TABLE IF EXISTS `user_edu_deposits_comment`;
CREATE TABLE IF NOT EXISTS `user_edu_deposits_comment` (
  `user_edu_deposits_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_deposits_comment_id`),
  UNIQUE KEY `user_edu_deposits_comment_id_UNIQUE` (`user_edu_deposits_comment_id`),
  KEY `fk_user_edu_deposits_comment_user_edu_deposits1_idx` (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_exercise_log`
--

DROP TABLE IF EXISTS `user_edu_exercise_log`;
CREATE TABLE IF NOT EXISTS `user_edu_exercise_log` (
  `user_edu_exercise_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `answer` enum('A','B','C','D') NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_exercise_log_id`),
  UNIQUE KEY `user_edu_exercise_log_id_UNIQUE` (`user_edu_exercise_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_fee_payment`
--

DROP TABLE IF EXISTS `user_edu_fee_payment`;
CREATE TABLE IF NOT EXISTS `user_edu_fee_payment` (
  `user_edu_fee_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(15) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_fee_payment_id`),
  UNIQUE KEY `user_edu_fee_payment_id_UNIQUE` (`user_edu_fee_payment_id`),
  UNIQUE KEY `reference_UNIQUE` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_student_log`
--

DROP TABLE IF EXISTS `user_edu_student_log`;
CREATE TABLE IF NOT EXISTS `user_edu_student_log` (
  `user_edu_student_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_lesson` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_student_log_id`),
  UNIQUE KEY `user_edu_student_log_id_UNIQUE` (`user_edu_student_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_support_answer`
--

DROP TABLE IF EXISTS `user_edu_support_answer`;
CREATE TABLE IF NOT EXISTS `user_edu_support_answer` (
  `user_edu_support_answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(11) NOT NULL,
  `category` enum('1','2') NOT NULL,
  `request_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_support_answer_id`),
  KEY `fk_user_edu_support_answer_user_edu_support_request1_idx` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_edu_support_request`
--

DROP TABLE IF EXISTS `user_edu_support_request`;
CREATE TABLE IF NOT EXISTS `user_edu_support_request` (
  `user_edu_support_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `support_request_code` varchar(20) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `request` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Open\n2 - Closed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_support_request_id`),
  UNIQUE KEY `support_request_code_UNIQUE` (`support_request_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_ifxaccount`
--

DROP TABLE IF EXISTS `user_ifxaccount`;
CREATE TABLE IF NOT EXISTS `user_ifxaccount` (
  `ifxaccount_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `ifx_acct_no` varchar(11) NOT NULL,
  `is_bonus_account` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `partner_code` varchar(4) NOT NULL DEFAULT 'BBLR',
  `type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - ILPR\n2 - Non-ILPR',
  `status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 - New2 - Active3 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ifxaccount_id`),
  UNIQUE KEY `ifxaccount_id_UNIQUE` (`ifxaccount_id`),
  UNIQUE KEY `ifx_acct_no_UNIQUE` (`ifx_acct_no`),
  KEY `fk_ifxaccount_user1_idx` (`user_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_ilpr_enrolment`
--

DROP TABLE IF EXISTS `user_ilpr_enrolment`;
CREATE TABLE IF NOT EXISTS `user_ilpr_enrolment` (
  `user_ilpr_enrolment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ifxaccount_id` int(11) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Moderated',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_ilpr_enrolment_id`),
  KEY `fk_user_ilpr_enrolment_user_ifxaccount1_idx` (`ifxaccount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
CREATE TABLE IF NOT EXISTS `user_meta` (
  `user_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_meta_id`),
  UNIQUE KEY `meta_id_UNIQUE` (`user_meta_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  KEY `fk_user_meta_state1_idx` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscription`
--

DROP TABLE IF EXISTS `user_subscription`;
CREATE TABLE IF NOT EXISTS `user_subscription` (
  `user_subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `campaign_category_id` int(11) NOT NULL,
  `campaign_email_id` int(11) DEFAULT NULL,
  `campaign_sms_id` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_subscription_id`),
  UNIQUE KEY `user_subscription_id_UNIQUE` (`user_subscription_id`),
  KEY `fk_subscription_user1_idx` (`user_code`),
  KEY `fk_user_subscription_campaign_category1_idx` (`campaign_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_val_2017`
--

DROP TABLE IF EXISTS `user_val_2017`;
CREATE TABLE IF NOT EXISTS `user_val_2017` (
  `user_val_2017_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) NOT NULL,
  `val_pics` varchar(255) NOT NULL,
  `val_message` text,
  `admin_comment` text,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_val_2017_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  UNIQUE KEY `val_pics_UNIQUE` (`val_pics`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_verification`
--

DROP TABLE IF EXISTS `user_verification`;
CREATE TABLE IF NOT EXISTS `user_verification` (
  `verification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(11) DEFAULT NULL,
  `phone_code` varchar(6) DEFAULT NULL,
  `phone_status` enum('1','2') DEFAULT '1' COMMENT '1 - New2 - Used',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`verification_id`),
  UNIQUE KEY `verification_id_UNIQUE` (`verification_id`),
  UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  KEY `fk_user_verification_user1_idx` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_withdrawal`
--

DROP TABLE IF EXISTS `user_withdrawal`;
CREATE TABLE IF NOT EXISTS `user_withdrawal` (
  `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` varchar(15) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `exchange_rate` decimal(10,2) DEFAULT NULL,
  `dollar_withdraw` decimal(10,2) NOT NULL,
  `naira_equivalent_dollar_withdraw` decimal(10,2) NOT NULL,
  `naira_service_charge` decimal(10,2) NOT NULL,
  `naira_vat_charge` decimal(10,2) NOT NULL,
  `naira_total_withdrawable` decimal(10,2) NOT NULL,
  `client_phone_password` varchar(30) NOT NULL,
  `client_comment` varchar(255) DEFAULT NULL,
  `transfer_reference` text,
  `status` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1' COMMENT '1 - Withdrawal Initiated\n2 - Account Check In Progress\n3 - Account Check Failed\n4 - Account Check Successful\n5 - Withdrawal In Progress\n6 - Withdrawal Declined\n7 - Withdrawal Successful\n8 - Payment In Progress\n9 - Payment Declined\n10 - Payment Made / Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`withdrawal_id`),
  UNIQUE KEY `trans_id_UNIQUE` (`trans_id`),
  UNIQUE KEY `withdrawal_id_UNIQUE` (`withdrawal_id`),
  KEY `fk_withdrawal_ifxaccount1_idx` (`ifxaccount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `visitor_id` int(1) NOT NULL AUTO_INCREMENT,
  `visitor_name` varchar(500) NOT NULL,
  `visitor_email` varchar(500) NOT NULL,
  `block_status` varchar(3) NOT NULL DEFAULT 'OFF',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`visitor_email`),
  UNIQUE KEY `visitor_id` (`visitor_id`),
  UNIQUE KEY `visitor_id_2` (`visitor_id`,`visitor_email`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_comment`
--

DROP TABLE IF EXISTS `withdrawal_comment`;
CREATE TABLE IF NOT EXISTS `withdrawal_comment` (
  `withdrawal_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` varchar(15) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL COMMENT 'For comments entered by Admin\nAlso for system comments, e.g. Admin change to funded',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`withdrawal_comment_id`),
  UNIQUE KEY `withdrawal_comment_id_UNIQUE` (`withdrawal_comment_id`),
  KEY `fk_withdrawal_comment_user_withdrawal1_idx` (`trans_id`),
  KEY `fk_withdrawal_comment_admin1_idx` (`admin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_monitoring`
--

DROP TABLE IF EXISTS `withdrawal_monitoring`;
CREATE TABLE IF NOT EXISTS `withdrawal_monitoring` (
  `withdrawal_monitoring_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_code` varchar(5) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`withdrawal_monitoring_id`),
  UNIQUE KEY `transaction_monitoring_id_UNIQUE` (`withdrawal_monitoring_id`),
  KEY `fk_transaction_monitoring_admin1_idx` (`admin_code`),
  KEY `fk_withdrawal_user_deposit10_idx` (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_privilege`
--
ALTER TABLE `admin_privilege`
  ADD CONSTRAINT `fk_admin_privilege_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `campaign_email`
--
ALTER TABLE `campaign_email`
  ADD CONSTRAINT `fk_campaign_email_campaign_category1` FOREIGN KEY (`campaign_category_id`) REFERENCES `campaign_category` (`campaign_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `campaign_email_solo`
--
ALTER TABLE `campaign_email_solo`
  ADD CONSTRAINT `fk_campaign_email_solo_campaign_email_solo_group1` FOREIGN KEY (`solo_group`) REFERENCES `campaign_email_solo_group` (`campaign_email_solo_group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `campaign_sms`
--
ALTER TABLE `campaign_sms`
  ADD CONSTRAINT `fk_campaign_sms_campaign_category1` FOREIGN KEY (`campaign_category_id`) REFERENCES `campaign_category` (`campaign_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deposit_comment`
--
ALTER TABLE `deposit_comment`
  ADD CONSTRAINT `fk_deposit_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deposit_comment_user_deposit1` FOREIGN KEY (`trans_id`) REFERENCES `user_deposit` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deposit_monitoring`
--
ALTER TABLE `deposit_monitoring`
  ADD CONSTRAINT `fk_deposit_monitoring_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deposit_user_deposit1` FOREIGN KEY (`trans_id`) REFERENCES `user_deposit` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `dinner2016_comment`
--
ALTER TABLE `dinner2016_comment`
  ADD CONSTRAINT `fk_dinner2016_comment_dinner_20161` FOREIGN KEY (`dinner_id`) REFERENCES `dinner_2016` (`id_dinner_2016`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `edu_lesson`
--
ALTER TABLE `edu_lesson`
  ADD CONSTRAINT `fk_edu_lesson_edu_course1` FOREIGN KEY (`course_id`) REFERENCES `edu_course` (`edu_course_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `edu_lesson_exercise`
--
ALTER TABLE `edu_lesson_exercise`
  ADD CONSTRAINT `fk_edu_lesson_exercise_edu_lesson1` FOREIGN KEY (`lesson_id`) REFERENCES `edu_lesson` (`edu_lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `facility_inventory`
--
ALTER TABLE `facility_inventory`
  ADD CONSTRAINT `fk_facility_inventory_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_inventory_facility_category1` FOREIGN KEY (`category_id`) REFERENCES `facility_category` (`facility_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_inventory_facility_station1` FOREIGN KEY (`station_id`) REFERENCES `facility_station` (`facility_station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `facility_request`
--
ALTER TABLE `facility_request`
  ADD CONSTRAINT `fk_facility_request_admin1` FOREIGN KEY (`author`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_facility_category1` FOREIGN KEY (`category_id`) REFERENCES `facility_category` (`facility_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_facility_station1` FOREIGN KEY (`station_id`) REFERENCES `facility_station` (`facility_station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `facility_request_comment`
--
ALTER TABLE `facility_request_comment`
  ADD CONSTRAINT `fk_facility_request_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_comment_facility_request1` FOREIGN KEY (`request_id`) REFERENCES `facility_request` (`facility_request_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `free_training_campaign_comment`
--
ALTER TABLE `free_training_campaign_comment`
  ADD CONSTRAINT `fk_free_training_campaign_comment_free_training_campaign1` FOREIGN KEY (`training_campaign_id`) REFERENCES `free_training_campaign` (`free_training_campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `partner`
--
ALTER TABLE `partner`
  ADD CONSTRAINT `fk_partner_user_ifxaccount1` FOREIGN KEY (`settlement_ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `partner_financial_activity_commission`
--
ALTER TABLE `partner_financial_activity_commission`
  ADD CONSTRAINT `fk_partner_financial_activity_commission_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `partner_trading_commission`
--
ALTER TABLE `partner_trading_commission`
  ADD CONSTRAINT `fk_partner_payment_trading_commission_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `partner_withdrawal`
--
ALTER TABLE `partner_withdrawal`
  ADD CONSTRAINT `fk_partner_withdrawal_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_partner_withdrawal_user_ifxaccount1` FOREIGN KEY (`account_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_partner_withdrawalt_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `point_based_claimed`
--
ALTER TABLE `point_based_claimed`
  ADD CONSTRAINT `fk_point_based_claimed_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `point_based_reward`
--
ALTER TABLE `point_based_reward`
  ADD CONSTRAINT `fk_points_based_reward_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `point_ranking_log`
--
ALTER TABLE `point_ranking_log`
  ADD CONSTRAINT `fk_point_ranking_log_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `snappy_help`
--
ALTER TABLE `snappy_help`
  ADD CONSTRAINT `fk_snappy_help_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `fk_state_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `trading_commission`
--
ALTER TABLE `trading_commission`
  ADD CONSTRAINT `fk_user_trading_commission_currency1` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `train_questions`
--
ALTER TABLE `train_questions`
  ADD CONSTRAINT `fk_train_questions_edu_course1` FOREIGN KEY (`course_id`) REFERENCES `edu_course` (`edu_course_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_train_questions_edu_lesson1` FOREIGN KEY (`lesson_id`) REFERENCES `edu_lesson` (`edu_lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `train_question_options`
--
ALTER TABLE `train_question_options`
  ADD CONSTRAINT `fk_train_question_options_train_questions1` FOREIGN KEY (`question_id`) REFERENCES `train_questions` (`question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_account_flag`
--
ALTER TABLE `user_account_flag`
  ADD CONSTRAINT `fk_user_account_flag_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_account_flag_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_bank`
--
ALTER TABLE `user_bank`
  ADD CONSTRAINT `fk_bank_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_bank_nigerian_banks1` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_bonus_deposit`
--
ALTER TABLE `user_bonus_deposit`
  ADD CONSTRAINT `fk_user_bonus_deposit_user_deposit1` FOREIGN KEY (`user_deposit_id`) REFERENCES `user_deposit` (`user_deposit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_bonus_deposit_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_credential`
--
ALTER TABLE `user_credential`
  ADD CONSTRAINT `fk_credential_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_deposit`
--
ALTER TABLE `user_deposit`
  ADD CONSTRAINT `fk_deposit_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_deposit_meta`
--
ALTER TABLE `user_deposit_meta`
  ADD CONSTRAINT `fk_user_deposit_meta_user_deposit1` FOREIGN KEY (`user_deposit_id`) REFERENCES `user_deposit` (`user_deposit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_edu_deposits_comment`
--
ALTER TABLE `user_edu_deposits_comment`
  ADD CONSTRAINT `fk_user_edu_deposits_comment_user_edu_deposits1` FOREIGN KEY (`trans_id`) REFERENCES `user_edu_deposits` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_edu_fee_payment`
--
ALTER TABLE `user_edu_fee_payment`
  ADD CONSTRAINT `fk_user_edu_fee_payment_user_edu_deposits1` FOREIGN KEY (`reference`) REFERENCES `user_edu_deposits` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_edu_support_answer`
--
ALTER TABLE `user_edu_support_answer`
  ADD CONSTRAINT `fk_user_edu_support_answer_user_edu_support_request1` FOREIGN KEY (`request_id`) REFERENCES `user_edu_support_request` (`user_edu_support_request_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_ifxaccount`
--
ALTER TABLE `user_ifxaccount`
  ADD CONSTRAINT `fk_ifxaccount_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_ilpr_enrolment`
--
ALTER TABLE `user_ilpr_enrolment`
  ADD CONSTRAINT `fk_user_ilpr_enrolment_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD CONSTRAINT `fk_user_meta_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_meta_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
