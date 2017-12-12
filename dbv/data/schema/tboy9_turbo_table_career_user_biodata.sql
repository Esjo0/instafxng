
CREATE TABLE `career_user_biodata` (
  `career_user_biodata_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `cu_password` varchar(128) NOT NULL,
  `pass_salt` varchar(64) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `other_names` varchar(200) DEFAULT NULL,
  `phone_number` varchar(11) NOT NULL,
  `sex` enum('M','F') NOT NULL COMMENT 'M - Male\nF - Female',
  `marital_status` enum('S','M') NOT NULL COMMENT 'S - Single\nM - Married',
  `state_of_origin` int(11) NOT NULL,
  `dob` date NOT NULL,
  `address` text,
  `state` int(11) DEFAULT NULL,
  `is_active` enum('1','2') DEFAULT NULL COMMENT '1 - Yes\n2 - No',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
