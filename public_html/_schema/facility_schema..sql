CREATE TABLE `facility_category` (
 `facility_category_id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `description` varchar(255) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`facility_category_id`)
)

CREATE TABLE `facility_file` (
 `file_id` int(10) NOT NULL AUTO_INCREMENT,
 `name` varchar(10) NOT NULL,
 `path` text NOT NULL,
 `label` text,
 `created` timestamp(5) NOT NULL DEFAULT CURRENT_TIMESTAMP(5),
 PRIMARY KEY (`file_id`)
)



CREATE TABLE `facility_inventory` (
 `id` int(100) NOT NULL AUTO_INCREMENT,
 `invent_id` varchar(100) DEFAULT NULL,
 `name` varchar(100) DEFAULT NULL,
 `cost` int(100) DEFAULT NULL,
 `date` date DEFAULT NULL,
 `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 `admin` text,
 `users` text,
 `location` int(10) DEFAULT NULL,
 `category` varchar(255) DEFAULT NULL,
 UNIQUE KEY `id` (`id`)
)


CREATE TABLE `facility_location` (
 `location_id` int(11) NOT NULL AUTO_INCREMENT,
 `location` text NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(255) NOT NULL,
 UNIQUE KEY `location_id` (`location_id`)
)

CREATE TABLE `facility_report` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `invent_id` varchar(10) NOT NULL,
 `report` text NOT NULL,
 `admin` text NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `status` int(10) NOT NULL COMMENT 'pending = 1',
 `a_comment` text,
 UNIQUE KEY `id` (`id`)
)

CREATE TABLE `facility_request` (
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
 KEY `fk_facility_request_facility_category1_idx` (`category_id`),
 CONSTRAINT `fk_facility_request_admin1` FOREIGN KEY (`author`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
 CONSTRAINT `fk_facility_request_facility_category1` FOREIGN KEY (`category_id`) REFERENCES `facility_category` (`facility_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
 CONSTRAINT `fk_facility_request_facility_station1` FOREIGN KEY (`station_id`) REFERENCES `facility_station` (`facility_station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
)

CREATE TABLE `facility_servicing` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `invent_id` varchar(10) NOT NULL,
 `cost` int(10) NOT NULL,
 `executor` varchar(10) NOT NULL,
 `type` text NOT NULL,
 `next` date NOT NULL,
 `details` text NOT NULL,
 `date` timestamp(5) NOT NULL DEFAULT CURRENT_TIMESTAMP(5),
 PRIMARY KEY (`id`)
)

CREATE TABLE `facility_work` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `title` varchar(100) NOT NULL,
 `details` text NOT NULL,
 `location` varchar(10) NOT NULL,
 `created_by` varchar(10) NOT NULL,
 `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 `status` text NOT NULL,
 `pro` int(11) DEFAULT NULL,
 `priority` text NOT NULL,
 `assign` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `id` (`id`)
)

