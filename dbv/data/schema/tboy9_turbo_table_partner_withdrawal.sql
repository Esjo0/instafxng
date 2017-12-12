
CREATE TABLE `partner_withdrawal` (
  `partner_payment_id` int(11) NOT NULL,
  `transaction_id` varchar(15) NOT NULL,
  `admin_code` varchar(5) DEFAULT NULL,
  `partner_code` varchar(5) NOT NULL,
  `account_id` int(11) NOT NULL COMMENT 'ifxacccount_id, bank_account_id',
  `amount` decimal(10,2) NOT NULL,
  `trans_type` enum('1','2') DEFAULT NULL COMMENT '1 - IFX Payment \n2 - Bank Payment',
  `transfer_reference` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('1','2','3') NOT NULL COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
