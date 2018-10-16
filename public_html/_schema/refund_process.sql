ALTER TABLE `user_deposit` CHANGE `status` `status` ENUM('1','2','3','4','5','6','7','8','9','10','11') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated2 - Notified3 - Confirmation In Progress4 - Confirmation Declined5 - Confirmed6 - Funding In Progress7 - Funding Declined8 - Funded / Completed9 - Payment Failed10 - Expired 11-Refunded';

CREATE TABLE `user_deposit_refund` (
 `refund_id` int(11) NOT NULL AUTO_INCREMENT,
 `refund_type` enum('1','2','3') NOT NULL,
 `transaction_id` varchar(100) NOT NULL,
 `amount_paid` int(11) NOT NULL,
 `user_bank_name` varchar(100) DEFAULT NULL,
 `user_acct_name` varchar(100) DEFAULT NULL,
 `user_acct_no` int(11) DEFAULT NULL,
 `payment_method` varchar(100) DEFAULT NULL,
 `company_bank_name` varchar(100) DEFAULT NULL,
 `company_acct_name` varchar(100) DEFAULT NULL,
 `company_acct_no` int(11) DEFAULT NULL,
 `issue_desc` text,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `refund_status` set('0','1','2') DEFAULT '1' COMMENT '1-Pending 2-Completed',
 `refund_complete_time` datetime DEFAULT NULL,
 UNIQUE KEY `refund_id` (`refund_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1