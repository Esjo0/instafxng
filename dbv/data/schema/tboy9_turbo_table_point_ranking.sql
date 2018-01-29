
CREATE TABLE `point_ranking` (
  `point_ranking_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `year_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `year_earned_archive` decimal(10,2) NOT NULL DEFAULT '0.00',
  `year_rank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_earned_archive` decimal(10,2) NOT NULL DEFAULT '0.00',
  `month_rank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point_claimed` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
