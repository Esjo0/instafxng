
CREATE TABLE `event_reg` (
  `event_reg_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email_address` varchar(45) NOT NULL,
  `ifx_acct_no` varchar(11) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
