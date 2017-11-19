
CREATE TABLE `user_account_flag` (
  `user_account_flag_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
