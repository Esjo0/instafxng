
CREATE TABLE `system_setting` (
  `system_setting_id` int(11) NOT NULL,
  `constant` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Rates\n2 - Schedules',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
