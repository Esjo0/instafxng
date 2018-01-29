
CREATE TABLE `user_withdrawal` (
  `withdrawal_id` int(11) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `ifxaccount_id` int(11) NOT NULL,
  `exchange_rate` decimal(10,2) DEFAULT NULL,
  `dollar_withdraw` decimal(10,2) NOT NULL,
  `naira_equivalent_dollar_withdraw` decimal(10,2) NOT NULL,
  `naira_service_charge` decimal(10,2) NOT NULL,
  `naira_vat_charge` decimal(10,2) NOT NULL,
  `naira_total_withdrawable` decimal(10,2) NOT NULL,
  `client_phone_password` varchar(30) NOT NULL,
  `client_comment` varchar(255) DEFAULT NULL,
  `transfer_reference` text,
  `status` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1' COMMENT '1 - Withdrawal Initiated\n2 - Account Check In Progress\n3 - Account Check Failed\n4 - Account Check Successful\n5 - Withdrawal In Progress\n6 - Withdrawal Declined\n7 - Withdrawal Successful\n8 - Payment In Progress\n9 - Payment Declined\n10 - Payment Made / Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
