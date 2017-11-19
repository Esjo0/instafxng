
CREATE TABLE `user_edu_support_request` (
  `user_edu_support_request_id` int(11) NOT NULL,
  `support_request_code` varchar(20) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `request` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Open\n2 - Closed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
