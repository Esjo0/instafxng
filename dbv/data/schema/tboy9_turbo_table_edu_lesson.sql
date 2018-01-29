
CREATE TABLE `edu_lesson` (
  `edu_lesson_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_order` int(11) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `time_required` varchar(45) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
