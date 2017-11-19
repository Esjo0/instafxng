
CREATE TABLE `campaign_email_track` (
  `campaign_track_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `recipient_query` text NOT NULL,
  `total_recipient` int(11) NOT NULL,
  `current_offset` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
