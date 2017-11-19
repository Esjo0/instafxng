
CREATE TABLE `user_edu_deposits` (
  `user_edu_deposits_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `course_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stamp_duty` decimal(10,2) DEFAULT NULL,
  `gateway_charge` decimal(10,2) DEFAULT NULL,
  `pay_method` enum('1','2','3','4','5','6','7','8') NOT NULL DEFAULT '1' COMMENT '1 - WebPay\n2 - Internet Transfer\n3 - ATM Transfer\n4 - Bank Transfer\n5 - Mobile Money Transfer\n6 - Cash Deposit\n7 - Office Payment\n8 - Not Listed',
  `deposit_origin` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Online\n2 - Diamond Office\n3 - Eastline Office',
  `status` enum('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated\n2 - Notified\n3 - Confirmed\n4 - Declined\n5 - Failed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
