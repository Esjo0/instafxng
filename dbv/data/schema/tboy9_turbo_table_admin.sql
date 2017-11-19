
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass_salt` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive\n3 - Suspended',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
