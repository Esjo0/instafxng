
CREATE TABLE `edu_lesson_exercise` (
  `edu_lesson_exercise_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text,
  `option_d` text,
  `right_option` enum('A','B','C','D') NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
