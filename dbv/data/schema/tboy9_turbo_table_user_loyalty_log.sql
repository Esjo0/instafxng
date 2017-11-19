
CREATE TABLE `user_loyalty_log` (
  `user_loyalty_log_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `total_point_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_point_earned_lagged` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'The total points earned by a client in the entire loyalty program until exactly 1 year ago. i.e. if today is 10-Oct-2017, this value will cover from Birth till 10-Oct-2016.',
  `total_point_claimed` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `expired_point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
