
CREATE TABLE `user_credential` (
  `user_credential_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `idcard` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL COMMENT '1 - Passport\n2 - ID Card\n3 - Signature',
  `signature` varchar(255) DEFAULT NULL,
  `doc_status` enum('000','001','010','011','100','101','110','111') DEFAULT '000' COMMENT '000 - None Approved\n001 - Signature Approved\n010 - Passport Approved\n011 - Passport and Signature Approved\n100 - Idcard Approved\n101 - Idcard and Signature Approved\n110 - Idcard and Passport Approved\n111 - All Approved',
  `remark` varchar(255) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation\n2 - Moderated',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
