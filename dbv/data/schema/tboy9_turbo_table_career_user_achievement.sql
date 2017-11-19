
CREATE TABLE `career_user_achievement` (
  `career_user_achievement_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `achieve_title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `category` enum('1','2','3','4') NOT NULL COMMENT '1 - Certification\n2 - Course\n3 - Honour / Award\n4 - Project',
  `achieve_date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
