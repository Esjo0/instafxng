-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2017 at 08:26 AM
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
  MODIFY `req_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
