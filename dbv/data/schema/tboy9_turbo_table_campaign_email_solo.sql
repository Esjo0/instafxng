
CREATE TABLE `campaign_email_solo` (
  `campaign_email_solo_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `solo_group` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Inactive',
  `day_to_send` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
