
CREATE TABLE `career_user_application` (
  `career_user_application_id` int(11) NOT NULL,
  `cu_user_code` varchar(7) NOT NULL,
  `job_code` varchar(6) NOT NULL,
  `status` enum('1','2','3','4','5','6','7') NOT NULL DEFAULT '1' COMMENT '1 - New 2 - Submitted 3 - Review 4 - No Review 5 - Interviewed 6 - Employed 7 - Not Employed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
