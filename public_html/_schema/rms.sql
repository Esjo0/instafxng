CREATE TABLE `rms_reports` (
 `report_id` int(11) NOT NULL AUTO_INCREMENT,
 `report` text NOT NULL,
 `window_period` varchar(50) NOT NULL,
 `reviewed` text,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(10) NOT NULL,
 `target_id` int(11) DEFAULT NULL,
 PRIMARY KEY (`report_id`),
 UNIQUE KEY `report_id` (`report_id`)
)

CREATE TABLE `rms_report_comments` (
 `comment_id` int(11) NOT NULL AUTO_INCREMENT,
 `report_id` int(11) NOT NULL,
 `comment` text NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(10) NOT NULL,
 PRIMARY KEY (`comment_id`),
 UNIQUE KEY `comment_id` (`comment_id`)
)

CREATE TABLE `rms_review_settings` (
 `review_id` int(11) NOT NULL AUTO_INCREMENT,
 `reviewers` text NOT NULL,
 `admin_code` varchar(50) NOT NULL,
 PRIMARY KEY (`review_id`),
 UNIQUE KEY `admin_code` (`admin_code`)
)

CREATE TABLE `rms_targets` (
 `target_id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `description` text NOT NULL,
 `window_period` varchar(100) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(10) NOT NULL,
 `reportees` text NOT NULL,
 `status` enum('0','1','2') NOT NULL DEFAULT '0',
 PRIMARY KEY (`target_id`)
)