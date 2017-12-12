
CREATE TABLE `point_based_reward` (
  `point_based_reward_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `point_earned` decimal(10,2) NOT NULL,
  `rate_used` decimal(10,2) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Deposit\n2 - Trading',
  `reference` int(11) NOT NULL,
  `is_active` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Yes 2 - No',
  `date_earned` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
