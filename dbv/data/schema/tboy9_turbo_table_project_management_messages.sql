
CREATE TABLE `project_management_messages` (
  `message_id` int(11) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
