
CREATE TABLE `partner_balance` (
  `partner_balance_id` int(11) NOT NULL,
  `partner_code` varchar(5) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1 - Trading\n2 - Financial',
  `balance` decimal(10,2) NOT NULL,
  `createad` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
