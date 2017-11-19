
CREATE TABLE `campaign_sms` (
  `campaign_sms_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `campaign_category_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `send_status` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `status` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Approved\n4 - Disapproved\n5 - In Progress\n6 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
