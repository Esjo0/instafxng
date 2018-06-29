CREATE TABLE `bonus_accounts` (
 `bonus_account_id` int(11) NOT NULL AUTO_INCREMENT,
 `ifx_account_id` int(11) NOT NULL,
 `bonus_code` varchar(10) NOT NULL,
 `enrolment_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0-Pending 1-declined 2-approved',
 `allocation_status` enum('1','2') DEFAULT NULL COMMENT '1-allocated 2-not allocated',
 `allocation_date` datetime DEFAULT NULL,
 `allocated_amount` decimal(10,0) DEFAULT NULL,
 `admin_code` varchar(10) DEFAULT NULL,
 `bonus_status` enum('1','2','3') DEFAULT NULL COMMENT '1-Bonus Live  2-Bonus Withdrawn 3-Bonus Expired',
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` datetime DEFAULT NULL,
 PRIMARY KEY (`bonus_account_id`),
 UNIQUE KEY `bonus_account_id` (`bonus_account_id`)
)

CREATE TABLE `bonus_acc_condition_meta` (
 `bonus_acc_condition_meta_id` int(11) NOT NULL AUTO_INCREMENT,
 `bonus_account_id` int(11) NOT NULL,
 `condition_id` int(11) NOT NULL,
 `status_id` enum('0','1') NOT NULL COMMENT '0-Condition Not Met Yet    1-Condition Met',
 PRIMARY KEY (`bonus_acc_condition_meta_id`),
 UNIQUE KEY `bonus_acc_condition_meta_id` (`bonus_acc_condition_meta_id`)
)

CREATE TABLE `bonus_app_meta` (
 `bonus_app_meta_id` int(11) NOT NULL AUTO_INCREMENT,
 `app_id` int(11) NOT NULL,
 `comments` varchar(255) NOT NULL,
 `admin_code` varchar(10) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`bonus_app_meta_id`)
)


CREATE TABLE `bonus_condition` (
 `condition_id` int(11) NOT NULL AUTO_INCREMENT,
 `type` int(11) NOT NULL,
 `condition_desc` text,
 `condition_url` varchar(255) NOT NULL,
 `documentation` text,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`condition_id`),
 UNIQUE KEY `condition_id` (`condition_id`)
)

CREATE TABLE `bonus_packages` (
 `package_id` int(11) NOT NULL AUTO_INCREMENT,
 `bonus_code` varchar(10) NOT NULL,
 `bonus_title` text NOT NULL,
 `bonus_desc` text,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` datetime DEFAULT NULL,
 `condition_id` text,
 `status` enum('1','2','3') NOT NULL COMMENT '1-draft 2-active 3-inactive',
 `type` int(11) NOT NULL,
 `admin_code` varchar(10) NOT NULL,
 PRIMARY KEY (`package_id`),
 UNIQUE KEY `bonus_code` (`bonus_code`)
)


CREATE TABLE `bonus_package_meta` (
 `bonus_package_meta_id` int(11) NOT NULL AUTO_INCREMENT,
 `bonus_code` varchar(10) NOT NULL,
 `condition_id` int(11) NOT NULL,
 `meta_name` varchar(100) NOT NULL,
 `meta_value` varchar(100) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`bonus_package_meta_id`)
)