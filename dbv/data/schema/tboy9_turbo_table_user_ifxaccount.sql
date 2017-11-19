
CREATE TABLE `user_ifxaccount` (
  `ifxaccount_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `ifx_acct_no` varchar(11) NOT NULL,
  `is_bonus_account` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `partner_code` varchar(4) NOT NULL DEFAULT 'BBLR',
  `type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - ILPR\n2 - Non-ILPR',
  `status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 - New2 - Active3 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
