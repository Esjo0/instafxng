
CREATE TABLE `user_edu_exercise_log` (
  `user_edu_exercise_log_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `answer` enum('A','B','C','D') NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
