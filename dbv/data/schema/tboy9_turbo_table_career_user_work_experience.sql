
CREATE TABLE `career_user_work_experience` (
  `career_user_work_experience_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `job_title` varchar(200) NOT NULL,
  `company` varchar(255) NOT NULL,
  `location` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
