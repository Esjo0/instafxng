
CREATE TABLE `partner_payment` (
  `partner_pay_id` int(11) NOT NULL,
  `user_code` varchar(10) DEFAULT NULL,
  `partner_code` varchar(50) DEFAULT NULL,
  `account_id` varchar(30) DEFAULT NULL,
  `amount` double(10,2) DEFAULT NULL,
  `comment` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
