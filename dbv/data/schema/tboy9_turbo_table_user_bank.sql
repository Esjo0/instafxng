
CREATE TABLE `user_bank` (
  `user_bank_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `bank_acct_name` varchar(100) NOT NULL,
  `bank_acct_no` varchar(10) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `is_active` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
