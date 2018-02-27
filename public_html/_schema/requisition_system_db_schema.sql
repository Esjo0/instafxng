CREATE TABLE `accounting_system_budgets` (
 `budget_id` int(11) NOT NULL AUTO_INCREMENT,
 `month_year` varchar(255) NOT NULL,
 `amount` varchar(255) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(255) NOT NULL,
 UNIQUE KEY `budget_id` (`budget_id`)
)


CREATE TABLE `accounting_system_office_locations` (
 `location_id` int(11) NOT NULL AUTO_INCREMENT,
 `location` text NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `admin_code` varchar(255) NOT NULL,
 UNIQUE KEY `location_id` (`location_id`)
)



CREATE TABLE `accounting_system_refunds` (
 `refund_id` int(11) NOT NULL AUTO_INCREMENT,
 `req_order_code` varchar(255) NOT NULL,
 `item_id` int(11) NOT NULL,
 `spent` int(11) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`refund_id`)
)



CREATE TABLE `accounting_system_req_item` (
 `order_code` varchar(20) NOT NULL,
 `item_id` int(11) NOT NULL AUTO_INCREMENT,
 `item_desc` varchar(255) NOT NULL,
 `no_of_items` int(11) NOT NULL,
 `app_no_of_items` int(11) DEFAULT NULL,
 `unit_cost` int(11) NOT NULL,
 `app_unit_cost` int(11) DEFAULT NULL,
 `total_cost` int(11) NOT NULL,
 `app_total_cost` int(11) DEFAULT NULL,
 `item_app` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '0-Declined 1-Pending 2-Approved',
 PRIMARY KEY (`item_id`)
)


CREATE TABLE `accounting_system_req_order` (
 `req_order_id` int(11) NOT NULL AUTO_INCREMENT,
 `req_order_code` varchar(255) NOT NULL,
 `req_order_total` varchar(255) NOT NULL,
 `author_code` varchar(255) NOT NULL,
 `admin_code` varchar(255) DEFAULT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `status` enum('1','2','3','4','5') CHARACTER SET utf8 DEFAULT '1' COMMENT '1-PENDING   2-APPROVED  3-DECLINED',
 `comments` varchar(255) DEFAULT NULL,
 `payment_status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-PENDING   2-PAID',
 `location` int(11) NOT NULL,
 PRIMARY KEY (`req_order_id`),
 UNIQUE KEY `order_id` (`req_order_id`),
 UNIQUE KEY `req_order_code` (`req_order_code`)
) 