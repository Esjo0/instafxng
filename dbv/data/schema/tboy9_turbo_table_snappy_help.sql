
CREATE TABLE `snappy_help` (
  `snappy_help_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `content` text NOT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1 - Active\n2 - Inactive\n3 - Draft',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
