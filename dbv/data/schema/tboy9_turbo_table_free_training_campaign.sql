
CREATE TABLE `free_training_campaign` (
  `free_training_campaign_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `training_interest` enum('1','2') DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `training_centre` enum('1','2','3') DEFAULT NULL COMMENT '1 - Diamond Estate2 - Ikota Office3 - Online',
  `attendant` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
