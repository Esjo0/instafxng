
CREATE TABLE `point_based_claimed` (
  `point_based_claimed_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `point_claimed` decimal(10,2) NOT NULL,
  `dollar_amount` decimal(10,2) NOT NULL,
  `rate_used` decimal(10,2) NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Completed\n3 - Failed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
