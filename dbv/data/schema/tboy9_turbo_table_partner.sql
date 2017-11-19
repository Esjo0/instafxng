
CREATE TABLE `partner` (
  `partner_id` int(11) NOT NULL,
  `partner_code` varchar(5) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `settlement_ifxaccount_id` int(11) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Active\n3 - Inactive\n4 - Suspended',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
