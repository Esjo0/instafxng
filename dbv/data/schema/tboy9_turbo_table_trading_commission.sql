
CREATE TABLE `trading_commission` (
  `trading_commission_id` int(11) NOT NULL,
  `ifx_acct_no` varchar(11) NOT NULL,
  `volume` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date_earned` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
