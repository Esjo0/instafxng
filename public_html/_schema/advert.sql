CREATE TABLE `track_top_traders` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_code` varchar(100) NOT NULL,
 `status` enum('1','2') NOT NULL COMMENT '1-Contacted, 2-Not_Contacted',
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_code` (`user_code`)
) 

ALTER TABLE `dinner_2018` ADD `state` VARCHAR(100) NOT NULL AFTER `invite_code`, ADD `type` ENUM('1','2','3','4') NOT NULL COMMENT '1-Single 2-Double 3-VIP 4-VVIP' AFTER `state`;
ALTER TABLE `dinner_2018` ADD `name` VARCHAR(100) NULL AFTER `updated`, ADD `email` VARCHAR(100) NULL AFTER `name`, ADD `phone` VARCHAR(100) NULL AFTER `email`;
ALTER TABLE `dinner_2018` ADD UNIQUE(`user_code`);
ALTER TABLE `dinner_2018` ADD `gender` ENUM('1','2') NOT NULL COMMENT '1=male 2=femal' AFTER `town`;

CREATE TABLE `dinner_2018` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_code` varchar(10) NOT NULL,
 `choice` enum('1','2','3') NOT NULL COMMENT '1-Yes 2-Maybe 3-No',
 `title` varchar(10) NOT NULL,
 `town` varchar(100) NOT NULL,
 `invite_code` varchar(100) DEFAULT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` datetime DEFAULT NULL,
 PRIMARY KEY (`id`)
)


CREATE TABLE IF NOT EXISTS `retention_analytics` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_today` DATE NOT NULL,
  `m_client_to_retain` INT(11) NULL DEFAULT 0,
  `m_client_retained` INT(11) NULL DEFAULT 0,
  `m_retained_yesterday` INT(11) NULL DEFAULT 0,
  `q_client_to_retain` INT(11) NULL DEFAULT 0,
  `q_client_retained` INT(11) NULL DEFAULT 0,
  `q_retained_yesterday` INT(11) NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `date_today_UNIQUE` (`date_today` ASC))
ENGINE = InnoDB

ALTER TABLE ``campaign_category`` DROP INDEX ``client_group_UNIQUE``;

ALTER TABLE `campaign_leads` CHANGE `source` `source` ENUM('1','2','3','4') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT '1-Landing Page 2-Facebook 3-Signals 4-Tweeter';


CREATE TABLE `admin_targets` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `details` varchar(100) NOT NULL,
 `type` enum('1','2') NOT NULL COMMENT '1-On-boarding, 2-Retention',
 `period` varchar(10) NOT NULL,
 `year` year(4) NOT NULL,
 `value` int(11) NOT NULL,
 `status` enum('1','2') NOT NULL COMMENT '1-active, 2-InActive',
 `admin` varchar(11) NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` datetime DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1

ALTER TABLE `free_training_campaign` CHANGE `entry_point` `entry_point` ENUM('0','1','2','3','4','5','6','7') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '0. Website 1.  Incoming Calls 2. Whatsapp 3. Support Mails 4.Walk in Clients 5. Facebook Ads 6. Referals 7.Instagram';
ALTER TABLE `user_deposit_refund` ADD `refund_approve_time` DATETIME NULL AFTER `refund_status`;
ALTER TABLE `user_deposit_refund` ADD `admin` VARCHAR(10) NOT NULL AFTER `refund_complete_time`;
ALTER TABLE `user_deposit_refund` CHANGE `refund_status` `refund_status` SET('0','1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '0-Initiated 1-Pending 2-Approved 3-Completed';

ALTER TABLE `free_training_campaign` ADD `entry_point` ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '7' COMMENT '1. Incoming Calls 2. Whatsapp 3. Support Mails 4. Walk in Clients 5. Facebook Ads 6. Referrals 7. Website' AFTER `campaign_period`;


CREATE TABLE `black_friday_2018` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_code` varchar(100) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_code` (`user_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1


ALTER TABLE `user_deposit` CHANGE `client_pay_method` `client_pay_method` ENUM('1','2','3','4','5','6','7','8','9','10') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '1 - WebPay 2 - Internet Transfer 3 - ATM Transfer 4 - Bank Transfer 5 - Mobile Money Transfer 6 - Cash Deposit 7 - Office Funding 8 - Not Listed 9 - USSD 10 - Paystack';



ALTER TABLE `article` ADD `type` ENUM('1','2','3') NOT NULL COMMENT '1- Article, 2-Calendar, 3-Extras' AFTER `title`

ALTER TABLE `article_visitors` ADD `entry_point` ENUM('1','2','3') NOT NULL COMMENT '1- Article, 2-Calendar, 3-Extras' AFTER `block_status`;

ALTER TABLE `article` ADD `scheduled_date` DATE NULL AFTER `updated`;

--Campaign Leads correction

DELETE FROM campaign_leads WHERE f_name IN ('Ayobami', 'IpayeShittaAdesina', 'mayowa', 'AbbasAbdulmalik', 'YomadeAyinla', 'NdidiOjei', 'AgbebakuWilson', 'UtojiubasSignature', 'RomeoFaithIbiso', 'KufreOffiong', 'IsraelTundeFayeun', 'DosunmuToheebAdemola', 'AkinsanmiAyomide', 'ESIAYOENTVARTISTEPRO', 'AbiodunAkinduro', 'JoshuaJerry', 'AhmadUsmanAhmad', 'AdamsAl-Danjuma', 'AmychichiPrincewill', 'SalisuInusa', 'OlubukolaOpeyemiOgunsanmi')

CREATE TABLE IF NOT EXISTS `independence_promo_date` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `date_earned` DATE NOT NULL,
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE INDEX `date_earned_UNIQUE` (`date_earned` ASC))
 ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `independence_promo` (
 `promo_id` INT(10) NOT NULL AUTO_INCREMENT,
 `ifx_acct_no` VARCHAR(10) NOT NULL,
 `user_code` VARCHAR(10) NOT NULL,
 `total_points` DECIMAL(10,2) NOT NULL,
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`promo_id`),
 UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
 UNIQUE INDEX `ifx_acct_no_UNIQUE` (`ifx_acct_no` ASC))
 ENGINE = InnoDB

CREATE TABLE `advert_div` (
 `advert_id` int(10) NOT NULL AUTO_INCREMENT,
 `title` varchar(50) NOT NULL,
 `content` text NOT NULL,
 `status` enum('1','2') NOT NULL COMMENT '1- Display, 2- Hide',
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `created_by` varchar(10) NOT NULL,
 PRIMARY KEY (`advert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1


CREATE TABLE `unverified_campaign_mail_log` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email_flag` enum('1','2','3','4','5') NOT NULL COMMENT '1-1stMail, 2-2ndMail, 3-3rdMail, 4-4thMail, 5-5thMail',
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `email` varchar(100) NOT NULL,
 UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1

//verification pending
ALTER TABLE `user_credential` CHANGE `status` `status` ENUM('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation2 - Moderated3 - Pending';


-- //User deposit refund table
CREATE TABLE `user_deposit_refund` (
 `refund_id` int(11) NOT NULL AUTO_INCREMENT,
 `ifx_acct_no` varchar(100) NOT NULL,
 `refund_type` enum('1','2','3') NOT NULL,
 `transaction_id` varchar(100) NOT NULL,
 `amount_paid` int(11) NOT NULL,
 `user_bank_name` varchar(100) NOT NULL,
 `user_acct_name` varchar(100) NOT NULL,
 `user_acct_no` int(11) NOT NULL,
 `payment_method` varchar(100) NOT NULL,
 `company_bank_name` varchar(100) NOT NULL,
 `company_acct_name` varchar(100) NOT NULL,
 `company_acct_no` int(11) NOT NULL,
 `issue_desc` text NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `refund_status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-Pending 2-Completed',
 UNIQUE KEY `refund_id` (`refund_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1