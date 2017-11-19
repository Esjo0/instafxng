
CREATE TABLE `project_management_tasks` (
  `task_id` int(11) NOT NULL,
  `task_code` varchar(255) NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text NOT NULL,
  `time_span` varchar(255) NOT NULL,
  `excecutors` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL,
  `start_stamp` timestamp NULL DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL,
  `completion_stamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
