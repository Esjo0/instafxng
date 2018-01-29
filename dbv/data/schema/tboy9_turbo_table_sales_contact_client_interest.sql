
CREATE TABLE `sales_contact_client_interest` (
  `sales_contact_client_interest_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `interest_training` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_funding` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_bonus` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_investment` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_services` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_other` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
