
CREATE TABLE `career_user_skill` (
  `career_user_skill_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `skill_title` varchar(255) NOT NULL,
  `competency` enum('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1 - Beginner\n2 - Advanced\n3 - Professional\n4 - Master\n5 - Certified',
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
