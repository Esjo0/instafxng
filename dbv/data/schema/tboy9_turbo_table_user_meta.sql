
CREATE TABLE `user_meta` (
  `user_meta_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
