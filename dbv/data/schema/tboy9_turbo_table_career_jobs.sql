
CREATE TABLE `career_jobs` (
  `career_jobs_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `job_code` varchar(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Closed\n2 - Open',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
