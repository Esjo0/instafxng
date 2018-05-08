CREATE TABLE `campaign_leads` (
 `lead_id` int(11) NOT NULL AUTO_INCREMENT,
 `f_name` varchar(50) NOT NULL,
 `m_name` varchar(50) DEFAULT NULL,
 `l_name` varchar(50) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `email` varchar(255) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `source` enum('1','2') NOT NULL COMMENT '1-Landing Page 2-Facebook',
 `state_id` int(11) DEFAULT NULL,
 `interest` enum('1','2') NOT NULL COMMENT '1-Training 2-ILPR',
 `sales_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-Not Contacted 1-Contacted',
 `updated` datetime DEFAULT NULL,
 PRIMARY KEY (`lead_id`),
 UNIQUE KEY `email` (`email`),
 UNIQUE KEY `lead_id` (`lead_id`)
)

CREATE TABLE `campaign_lead_comments` (
 `comment_id` int(11) NOT NULL AUTO_INCREMENT,
 `lead_id` int(11) NOT NULL,
 `comment` text NOT NULL,
 `admin_code` varchar(10) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`comment_id`),
 UNIQUE KEY `comment_id` (`comment_id`)
)