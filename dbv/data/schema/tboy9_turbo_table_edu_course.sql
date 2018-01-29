
CREATE TABLE `edu_course` (
  `edu_course_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `course_code` varchar(5) NOT NULL,
  `course_order` int(11) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `difficulty_level` enum('1','2','3','4') NOT NULL COMMENT '1 - Beginner\n2 - Intermediate\n3 - Advanced\n4 - Expert',
  `display_image` varchar(255) DEFAULT NULL,
  `intro_video_url` varchar(20) DEFAULT NULL,
  `time_required` varchar(45) DEFAULT NULL,
  `course_fee` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Free\n2 - Paid',
  `course_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` datetime NOT NULL,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
