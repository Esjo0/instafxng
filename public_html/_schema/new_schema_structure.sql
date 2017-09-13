
-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2017 at 10:04 AM
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

DROP TABLE `customers`;

--
-- Table structure for table `prospect_biodata`
--

CREATE TABLE `prospect_biodata` (
  `prospect_biodata_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `email_address` varchar(200) DEFAULT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `other_names` varchar(200) DEFAULT NULL,
  `phone_number` varchar(11) NOT NULL,
  `prospect_source` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prospect_sales_contact`
--

CREATE TABLE `prospect_sales_contact` (
  `prospect_sales_contact_id` int(11) NOT NULL,
  `prospect_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prospect_source`
--

CREATE TABLE `prospect_source` (
  `prospect_source_id` int(11) NOT NULL,
  `source_name` varchar(250) NOT NULL,
  `source_description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prospect_biodata`
--
ALTER TABLE `prospect_biodata`
  ADD PRIMARY KEY (`prospect_biodata_id`),
  ADD UNIQUE KEY `email_address_UNIQUE` (`email_address`),
  ADD KEY `fk_prospect_biodata_admin1_idx` (`admin_code`),
  ADD KEY `fk_prospect_biodata_prospect_source1_idx` (`prospect_source`);

--
-- Indexes for table `prospect_sales_contact`
--
ALTER TABLE `prospect_sales_contact`
  ADD PRIMARY KEY (`prospect_sales_contact_id`),
  ADD UNIQUE KEY `admin_code` (`admin_code`),
  ADD KEY `fk_prospect_sales_contact_admin1_idx` (`admin_code`),
  ADD KEY `fk_prospect_sales_contact_prospect_biodata1_idx` (`prospect_id`);

--
-- Indexes for table `prospect_source`
--
ALTER TABLE `prospect_source`
  ADD PRIMARY KEY (`prospect_source_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prospect_biodata`
--
ALTER TABLE `prospect_biodata`
  MODIFY `prospect_biodata_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `prospect_sales_contact`
--
ALTER TABLE `prospect_sales_contact`
  MODIFY `prospect_sales_contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `prospect_source`
--
ALTER TABLE `prospect_source`
  MODIFY `prospect_source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




--
-- Table structure for table `article_visitors`
--

CREATE TABLE `article_visitors` (
  `visitor_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `block_status` varchar(3) NOT NULL DEFAULT 'OFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article_visitors`
--
ALTER TABLE `article_visitors`
ADD PRIMARY KEY (`visitor_id`),
ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article_visitors`
--
ALTER TABLE `article_visitors`
MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--
-- Table structure for table `article_comments`
--

CREATE TABLE `article_comments` (
  `comment_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `article_id` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `reply_to` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(3) NOT NULL DEFAULT 'OFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article_comments`
--
ALTER TABLE `article_comments`
ADD PRIMARY KEY (`comment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article_comments`
--
ALTER TABLE `article_comments`
MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
