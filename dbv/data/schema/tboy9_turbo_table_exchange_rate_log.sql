
CREATE TABLE `exchange_rate_log` (
  `exchange_rate_log_id` int(11) NOT NULL,
  `change_date` date NOT NULL,
  `deposit_ilpr` decimal(10,2) NOT NULL,
  `deposit_nonilpr` decimal(10,2) NOT NULL,
  `withdraw` decimal(10,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
