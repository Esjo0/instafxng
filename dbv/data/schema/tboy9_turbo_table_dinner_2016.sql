
CREATE TABLE `dinner_2016` (
  `id_dinner_2016` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `invite` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - early 2 - late',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
