
CREATE TABLE `partner_financial_activity_commission` (
  `partner_financial_activity_commission_id` int(11) NOT NULL,
  `partner_code` varchar(5) NOT NULL,
  `reference_trans_id` varchar(15) NOT NULL COMMENT 'Gets input from the trans_id column of the following tables\n\nuser_deposit\nuser_withdrawal\npartner_payment',
  `amount` decimal(10,2) NOT NULL,
  `trans_type` enum('1','2','3') NOT NULL COMMENT '1 - Credit On User_deposit \n2 - Credit On User_withdrawal \n3 - Debit',
  `balance` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
