
CREATE TABLE `point_season` (
  `point_season_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly 2 - Yearly',
  `is_active` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
