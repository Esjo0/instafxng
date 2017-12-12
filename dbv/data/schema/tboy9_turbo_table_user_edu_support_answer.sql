
CREATE TABLE `user_edu_support_answer` (
  `user_edu_support_answer_id` int(11) NOT NULL,
  `author` varchar(11) NOT NULL,
  `category` enum('1','2') NOT NULL,
  `request_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
