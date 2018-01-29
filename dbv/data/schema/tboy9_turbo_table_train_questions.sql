
CREATE TABLE `train_questions` (
  `question_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `right_option` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
