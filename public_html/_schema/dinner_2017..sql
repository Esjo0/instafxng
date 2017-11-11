-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2017 at 01:48 PM
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
-- Table structure for table `dinner2017_comment`
--

CREATE TABLE `dinner2017_comment` (
 `comment_id` int(11) NOT NULL AUTO_INCREMENT,
 `reservation_code` varchar(11) NOT NULL,
 `admin_code` varchar(5) NOT NULL,
 `comment` text NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8

-- --------------------------------------------------------

--
-- Table structure for table `dinner_2017`
--

CREATE TABLE `dinner_2017` (
 `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
 `reservation_code` varchar(255) NOT NULL,
 `full_name` varchar(255) NOT NULL,
 `email` varchar(255) DEFAULT NULL,
 `phone` varchar(30) DEFAULT NULL,
 `ticket_type` enum('0','1','2','3','4','5') NOT NULL COMMENT '0-Single   1-Double    2-VIP_Single 3-VIP_Double  4-Hired_help   5-Staff',
 `confirmation` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0-Pending  1-Maybe  2-Confirmed  3-Declined',
 `invite` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-Not Sent  1-Sent',
 `state_of_residence` varchar(255) NOT NULL,
 `comments` text,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `attended` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-No   1-Yes',
 PRIMARY KEY (`reservation_id`),
 UNIQUE KEY `reservation_code` (`reservation_code`),
 UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dinner2017_comment`
--
ALTER TABLE `dinner2017_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
