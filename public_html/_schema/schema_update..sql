-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2017 at 11:12 AM
-- Server version: 5.7.11
-- PHP Version: 7.0.4

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
-- Table structure for table `accounting_system_budgets`
--

CREATE TABLE `accounting_system_budgets` (
  `budget_id` int(11) NOT NULL,
  `month_year` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_system_office_locations`
--

CREATE TABLE `accounting_system_office_locations` (
  `location_id` int(11) NOT NULL,
  `location` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_system_refunds`
--

CREATE TABLE `accounting_system_refunds` (
  `refund_id` int(11) NOT NULL,
  `req_order_code` varchar(255) NOT NULL,
  `actual_spent` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_system_req_order`
--

CREATE TABLE `accounting_system_req_order` (
  `req_order_id` int(11) NOT NULL,
  `req_order_code` varchar(255) NOT NULL,
  `req_order` text NOT NULL,
  `req_order_total` varchar(255) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `admin_code` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('1','2','3') DEFAULT '1' COMMENT '1-PENDING   2-APPROVED  3-DECLINED',
  `comments` varchar(255) DEFAULT NULL,
  `payment_status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-PENDING   2-PAID',
  `location` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_bulletin_comments`
--

CREATE TABLE `admin_bulletin_comments` (
  `comment_id` int(11) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `bulletin_id` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `reply_to` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_care_log`
--

CREATE TABLE `customer_care_log` (
  `log_id` int(11) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `con_desc` text NOT NULL,
  `tag` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-PENDING   2-TREATED',
  `log_type` enum('1','2') NOT NULL COMMENT '1-CLIENT   2-PROSPECT'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dinner2017_comment`
--

CREATE TABLE `dinner2017_comment` (
  `comment_id` int(11) NOT NULL,
  `reservation_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dinner_2017`
--

CREATE TABLE `dinner_2017` (
  `reservation_id` int(11) NOT NULL,
  `reservation_code` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `ticket_type` enum('0','1','2','3','4','5') NOT NULL COMMENT '0-Single   1-Double    2-VIP_Single 3-VIP_Double  4-Hired_help   5-Staff',
  `confirmation` enum('0','1','2','3') NOT NULL DEFAULT '0'COMMENT
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hr_attendance_log`
--

CREATE TABLE `hr_attendance_log` (
  `log_id` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` enum('1','2') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_messages`
--

CREATE TABLE `project_management_messages` (
  `message_id` int(11) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_projects`
--

CREATE TABLE `project_management_projects` (
  `project_id` int(11) NOT NULL,
  `project_code` varchar(500) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) DEFAULT 'IN PROGRESS',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supervisor_code` varchar(500) DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL,
  `last_comment` text,
  `executors` text,
  `completion_stamp` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_project_comments`
--

CREATE TABLE `project_management_project_comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_code` varchar(500) NOT NULL,
  `project_code` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_reminders`
--

CREATE TABLE `project_management_reminders` (
  `reminder_id` int(11) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `effect_date` varchar(500) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'ON',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_reports`
--

CREATE TABLE `project_management_reports` (
  `report_id` int(11) NOT NULL,
  `report_code` varchar(500) NOT NULL,
  `project_code` varchar(500) NOT NULL,
  `author_code` varchar(500) NOT NULL,
  `supervisor_code` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report` text NOT NULL,
  `comment` text,
  `status` varchar(255) DEFAULT 'PENDING'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_management_tasks`
--

CREATE TABLE `project_management_tasks` (
  `task_id` int(11) NOT NULL,
  `task_code` varchar(500) NOT NULL,
  `project_code` varchar(500) NOT NULL,
  `author_code` varchar(500) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text NOT NULL,
  `time_span` varchar(255) NOT NULL,
  `excecutors` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL,
  `start_stamp` timestamp NULL DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL,
  `completion_stamp` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `notification_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipients` text NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `notification_type` enum('1','2') NOT NULL COMMENT '1 - project_messages    2-bulletin_message'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_email_inbox`
--

CREATE TABLE `support_email_inbox` (
  `email_id` int(11) NOT NULL,
  `email_sender` varchar(1000) NOT NULL,
  `email_subject` varchar(1000) DEFAULT NULL,
  `email_body` text NOT NULL,
  `email_attacment_url` varchar(1000) DEFAULT NULL,
  `email_created` varchar(1000) NOT NULL,
  `email_admin_assigned` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_email_sent_box`
--

CREATE TABLE `support_email_sent_box` (
  `email_id` int(11) NOT NULL,
  `email_recipient` varchar(1000) DEFAULT NULL,
  `email_subject` varchar(1000) DEFAULT NULL,
  `email_body` text NOT NULL,
  `email_attacment_url` varchar(1000) DEFAULT NULL,
  `email_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_status` varchar(10) NOT NULL DEFAULT 'PENDING',
  `email_sender` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_email_settings`
--

CREATE TABLE `support_email_settings` (
  `setting_id` int(11) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `user_meta_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `d_o_b` varchar(255) DEFAULT NULL,
  `fb_name` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_system_budgets`
--
ALTER TABLE `accounting_system_budgets`
  ADD UNIQUE KEY `budget_id` (`budget_id`);

--
-- Indexes for table `accounting_system_office_locations`
--
ALTER TABLE `accounting_system_office_locations`
  ADD UNIQUE KEY `location_id` (`location_id`);

--
-- Indexes for table `accounting_system_refunds`
--
ALTER TABLE `accounting_system_refunds`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `accounting_system_req_order`
--
ALTER TABLE `accounting_system_req_order`
  ADD PRIMARY KEY (`req_order_id`),
  ADD UNIQUE KEY `order_id` (`req_order_id`),
  ADD UNIQUE KEY `req_order_code` (`req_order_code`);

--
-- Indexes for table `admin_bulletin_comments`
--
ALTER TABLE `admin_bulletin_comments`
  ADD UNIQUE KEY `comment_id` (`comment_id`);

--
-- Indexes for table `customer_care_log`
--
ALTER TABLE `customer_care_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `dinner2017_comment`
--
ALTER TABLE `dinner2017_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `project_management_messages`
--
ALTER TABLE `project_management_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `project_management_projects`
--
ALTER TABLE `project_management_projects`
  ADD PRIMARY KEY (`project_id`),
  ADD UNIQUE KEY `project_code` (`project_code`);

--
-- Indexes for table `project_management_project_comments`
--
ALTER TABLE `project_management_project_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `project_management_reminders`
--
ALTER TABLE `project_management_reminders`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `project_management_reports`
--
ALTER TABLE `project_management_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD UNIQUE KEY `report_code` (`report_code`);

--
-- Indexes for table `project_management_tasks`
--
ALTER TABLE `project_management_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD UNIQUE KEY `task_code` (`task_code`);

--
-- Indexes for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `support_email_inbox`
--
ALTER TABLE `support_email_inbox`
  ADD PRIMARY KEY (`email_id`);

--
-- Indexes for table `support_email_sent_box`
--
ALTER TABLE `support_email_sent_box`
  ADD PRIMARY KEY (`email_id`);

--
-- Indexes for table `support_email_settings`
--
ALTER TABLE `support_email_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`user_meta_id`),
  ADD UNIQUE KEY `meta_id_UNIQUE` (`user_meta_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD KEY `fk_user_meta_state1_idx` (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_system_budgets`
--
ALTER TABLE `accounting_system_budgets`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `accounting_system_office_locations`
--
ALTER TABLE `accounting_system_office_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `accounting_system_refunds`
--
ALTER TABLE `accounting_system_refunds`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `accounting_system_req_order`
--
ALTER TABLE `accounting_system_req_order`
  MODIFY `req_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `admin_bulletin_comments`
--
ALTER TABLE `admin_bulletin_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `customer_care_log`
--
ALTER TABLE `customer_care_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `dinner2017_comment`
--
ALTER TABLE `dinner2017_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `dinner_2017`
--
ALTER TABLE `dinner_2017`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `project_management_messages`
--
ALTER TABLE `project_management_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `project_management_projects`
--
ALTER TABLE `project_management_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `project_management_project_comments`
--
ALTER TABLE `project_management_project_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `project_management_reminders`
--
ALTER TABLE `project_management_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `project_management_reports`
--
ALTER TABLE `project_management_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `project_management_tasks`
--
ALTER TABLE `project_management_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `push_notifications`
--
ALTER TABLE `push_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `support_email_inbox`
--
ALTER TABLE `support_email_inbox`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `support_email_sent_box`
--
ALTER TABLE `support_email_sent_box`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `support_email_settings`
--
ALTER TABLE `support_email_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `user_meta_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD CONSTRAINT `fk_user_meta_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_meta_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
