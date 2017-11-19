
CREATE TABLE `project_management_reports` (
  `report_id` int(11) NOT NULL,
  `report_code` varchar(255) NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `supervisor_code` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report` text NOT NULL,
  `comment` text,
  `status` varchar(255) DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
