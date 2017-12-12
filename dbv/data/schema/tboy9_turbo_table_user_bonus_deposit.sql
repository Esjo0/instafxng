
CREATE TABLE `user_bonus_deposit` (
  `user_bonus_deposit_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `user_deposit_id` int(11) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
