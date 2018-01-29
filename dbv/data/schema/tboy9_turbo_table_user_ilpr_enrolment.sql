
CREATE TABLE `user_ilpr_enrolment` (
  `user_ilpr_enrolment_id` int(11) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Moderated',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
