
CREATE TABLE `free_training_campaign_comment` (
  `free_training_campaign_comment_id` int(11) NOT NULL,
  `training_campaign_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
