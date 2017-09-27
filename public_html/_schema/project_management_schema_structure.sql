-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2017 at 01:11 PM
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

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_management_messages`
--
ALTER TABLE `project_management_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `project_management_projects`
--
ALTER TABLE `project_management_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `project_management_project_comments`
--
ALTER TABLE `project_management_project_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `project_management_reminders`
--
ALTER TABLE `project_management_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `project_management_reports`
--
ALTER TABLE `project_management_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
