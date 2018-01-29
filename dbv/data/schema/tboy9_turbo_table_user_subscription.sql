
CREATE TABLE `user_subscription` (
  `user_subscription_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `campaign_category_id` int(11) NOT NULL,
  `campaign_email_id` int(11) DEFAULT NULL,
  `campaign_sms_id` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
