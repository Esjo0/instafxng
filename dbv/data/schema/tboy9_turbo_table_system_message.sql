
CREATE TABLE `system_message` (
  `system_message_id` int(11) NOT NULL,
  `constant` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `value` text NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Email\n2 - SMS',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
