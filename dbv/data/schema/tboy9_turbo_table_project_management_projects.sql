
CREATE TABLE `project_management_projects` (
  `project_id` int(11) NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) DEFAULT 'IN PROGRESS',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supervisor_code` varchar(500) DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL,
  `last_comment` text,
  `executors` text,
  `completion_stamp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
