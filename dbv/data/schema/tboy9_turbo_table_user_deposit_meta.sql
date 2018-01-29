
CREATE TABLE `user_deposit_meta` (
  `user_deposit_meta_id` int(11) NOT NULL,
  `user_deposit_id` int(11) NOT NULL,
  `trans_status_code` varchar(10) NOT NULL,
  `trans_status_message` varchar(255) NOT NULL,
  `trans_amount` decimal(10,2) NOT NULL,
  `trans_currency` varchar(3) NOT NULL DEFAULT '566',
  `gateway_name` varchar(10) NOT NULL,
  `full_verify_hash` varchar(128) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
