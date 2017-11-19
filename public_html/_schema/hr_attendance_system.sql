-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2017 at 12:20 PM
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
-- Table structure for table `hr_attendance_locations`
--

DROP TABLE IF EXISTS `hr_attendance_locations`;
CREATE TABLE `hr_attendance_locations` (
  `location_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_attendance_locations`
--

INSERT INTO `hr_attendance_locations` (`location_id`, `location`, `ip_address`, `created`) VALUES
  (1, 'Diamond Estate Office', '192.168.1.10', '2017-11-13 09:27:30'),
  (2, 'Diamond Estate Office', '169.254.206.99', '2017-11-13 09:28:57'),
  (3, 'HFP Eastline Office', '192.168.0.100', '2017-11-13 09:28:57'),
  (6, 'HFP Eastline Office', '192.168.8.101', '2017-11-13 09:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `hr_attendance_log`
--

DROP TABLE IF EXISTS `hr_attendance_log`;
CREATE TABLE `hr_attendance_log` (
  `log_id` int(11) NOT NULL,
  `date` varchar(100) CHARACTER SET utf8 NOT NULL,
  `time` varchar(100) CHARACTER SET utf8 NOT NULL,
  `admin_code` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indexes for table `hr_attendance_locations`
--
ALTER TABLE `hr_attendance_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexes for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_attendance_locations`
--
ALTER TABLE `hr_attendance_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
