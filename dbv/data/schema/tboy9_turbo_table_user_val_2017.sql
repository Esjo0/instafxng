
CREATE TABLE `user_val_2017` (
  `user_val_2017_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `val_pics` varchar(255) NOT NULL,
  `val_message` text,
  `admin_comment` text,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
