
CREATE TABLE `career_application_comment` (
  `career_application_comment_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
