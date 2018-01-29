
CREATE TABLE `point_ranking_log` (
  `point_ranking_log_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `position` int(2) NOT NULL,
  `point_earned` decimal(10,2) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly\n2 - Yearly',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
