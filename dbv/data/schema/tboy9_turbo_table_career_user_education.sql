
CREATE TABLE `career_user_education` (
  `career_user_education_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `field_of_study` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `grade` varchar(150) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `description` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
