
CREATE TABLE `user_edu_student_log` (
  `user_edu_student_log_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_lesson` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
