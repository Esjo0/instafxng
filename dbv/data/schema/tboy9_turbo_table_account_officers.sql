
CREATE TABLE `account_officers` (
  `account_officers_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
