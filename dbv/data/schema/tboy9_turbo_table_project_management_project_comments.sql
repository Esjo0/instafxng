
CREATE TABLE `project_management_project_comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_code` varchar(255) NOT NULL,
  `project_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
