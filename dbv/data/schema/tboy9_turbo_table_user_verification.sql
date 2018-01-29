
CREATE TABLE `user_verification` (
  `verification_id` int(11) NOT NULL,
  `user_code` varchar(11) DEFAULT NULL,
  `phone_code` varchar(6) DEFAULT NULL,
  `phone_status` enum('1','2') DEFAULT '1' COMMENT '1 - New2 - Used',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
