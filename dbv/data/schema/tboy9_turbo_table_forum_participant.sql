
CREATE TABLE `forum_participant` (
  `forum_participant_id` int(11) NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `middle_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8 NOT NULL,
  `venue` enum('1','2') CHARACTER SET utf8 NOT NULL COMMENT '1 - Diamond Estate\n2 - Eastline Complex',
  `forum_activate` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
