CREATE TABLE `sports_leads` (
 `lead_id` int(11) NOT NULL AUTO_INCREMENT,
 `phone` varchar(20) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `email` varchar(200) NOT NULL,
 `fullname` varchar(200) NOT NULL DEFAULT '0',
 `nickname` varchar(100) NOT NULL,
 `prediction` varchar(10) NOT NULL,
 PRIMARY KEY (`lead_id`),
 UNIQUE KEY `msg_id` (`lead_id`),
 UNIQUE KEY `email` (`email`,`nickname`)
);


CREATE TABLE `sports_leads_msgs` (
 `msg_id` int(11) NOT NULL AUTO_INCREMENT,
 `msg` varchar(255) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `email` varchar(200) NOT NULL,
 `nickname` varchar(200) NOT NULL,
 `likes` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`msg_id`)
) 