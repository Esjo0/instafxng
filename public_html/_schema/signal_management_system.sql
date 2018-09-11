ALTER TABLE `signal_daily` CHANGE `highest_pips` `highest_pips` INT(100) NULL DEFAULT '0', CHANGE `lowest_pips` `lowest_pips` INT(100) NULL DEFAULT '0';

ALTER TABLE `campaign_leads` CHANGE `interest` `interest` ENUM('1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT '1-Training 2-ILPR 3-Signals';

ALTER TABLE `campaign_leads` CHANGE `source` `source` ENUM('1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT '1-Landing Page 2-Facebook 3-Signals';



ALTER TABLE `signal_daily` ADD `lowest_pips_time` DATETIME(6) NULL DEFAULT NULL AFTER `highest_pips_time`,
 ADD `highest_pips` INT(100) NULL DEFAULT NULL AFTER `lowest_pips_time`,
 ADD `lowest_pips` INT(100) NULL DEFAULT NULL AFTER `highest_pips`;

ALTER TABLE `signal_daily` CHANGE `pips_time` `highest_pips_time` DATETIME(6) NULL DEFAULT NULL;

UPDATE signal_daily SET exit_time = '2018-08-10 14:58:00', entry_time = '2018-08-10 13:35:00' WHERE trigger_date = '2018-08-10' AND trigger_status = '2';

UPDATE signal_daily SET entry_time = '2018-08-10 14:15:00' WHERE trigger_date = '2018-08-10' AND trigger_status = '1';



UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'AUD/NZD'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'EUR/GBP'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'EUR/USD'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'GBP/USD'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'USD/CHF'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'USD/CAD'
UPDATE `signal_symbol` SET `decimal_place` = '2' WHERE `signal_symbol`.`symbol` = 'USD/JPY'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` ='AUD/USD'
UPDATE `signal_symbol` SET `decimal_place` = '2' WHERE `signal_symbol`.`symbol` = 'GBP/JPY'
UPDATE `signal_symbol` SET `decimal_place` = '2' WHERE `signal_symbol`.`symbol` = 'EUR/JPY'
UPDATE `signal_symbol` SET `decimal_place` = '4' WHERE `signal_symbol`.`symbol` = 'NZD/USD'

ALTER TABLE `signal_symbol` ADD `decimal_place` INT(10) NOT NULL AFTER `symbol`;


ALTER TABLE `signal_daily` ADD `pips_time` DATETIME(6) NULL AFTER `market_price`, ADD `display_status` INT(10) NULL COMMENT '0-unhide, 1-hide' AFTER `pips_time`;


UPDATE signal_daily SET pips = '0' WHERE trigger_date = '2018-08-09' AND trigger_status = '1';

ALTER TABLE `signal_daily` CHANGE `pips` `pips` INT(100) NULL DEFAULT NULL;

UPDATE signal_daily SET trigger_status = '1' WHERE signal_id = '75'
UPDATE signal_daily SET trigger_status = '1' WHERE signal_id = '78'

ALTER TABLE `signal_daily` ADD `market_price` DECIMAL(10,4) NOT NULL AFTER `created_by`;

ALTER TABLE `signal_daily` ADD `created_by` VARCHAR(100) NOT NULL AFTER `exit_price`;

ALTER TABLE `signal_daily` ADD `exit_price` DECIMAL(10,5) NULL DEFAULT NULL AFTER `exit_type`;

ALTER TABLE `signal_daily` DROP `views`;
ALTER TABLE `signal_daily` ADD `views` INT(11) NOT NULL AFTER `note`;
ALTER TABLE `signal_daily` CHANGE `pips` `pips` DECIMAL(10,4) NULL DEFAULT NULL;

INSERT INTO `system_setting` (constant, description, value, type) VALUE ('PARTNER_MINIMUM_WITHDRAWAL', 'The minimum dollar amount required for a partner to place withdrawal.', 5000, 1);

CREATE TABLE `signal_daily` (
 `signal_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol_id` int(11) NOT NULL,
 `order_type` enum('1','2') NOT NULL COMMENT '1-Buy 2-Sell',
 `price` decimal(10,5) NOT NULL,
 `take_profit` decimal(10,5) NOT NULL,
 `stop_loss` decimal(10,5) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `trigger_date` date NOT NULL,
 `trigger_time` time NOT NULL,
 `trend` enum('0','1') NOT NULL COMMENT '1-Bullish 0-Bearish',
 `note` text,
 `views` int(11) DEFAULT NULL,
 `trigger_status` int(11) DEFAULT '0' COMMENT '0=pending 1=active 2=closed',
 `entry_price` float DEFAULT NULL,
 `entry_time` datetime DEFAULT NULL,
 `exit_time` datetime DEFAULT NULL,
 `pips` datetime DEFAULT NULL,
 `exit_type` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`signal_id`),
 UNIQUE KEY `signal_id` (`signal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

CREATE TABLE `signal_quotes` (
 `signal_quote_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol_id` int(11) NOT NULL,
 `bid` float NOT NULL,
 `ask` float NOT NULL,
 `created` datetime NOT NULL,
 PRIMARY KEY (`signal_quote_id`),
 UNIQUE KEY `signal_quote_id` (`signal_quote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `signal_symbol` (
 `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol` varchar(40) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`symbol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


 	CREATE TABLE `signal_users` (
 `signal_user_id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `email` varchar(100) NOT NULL,
 `token` varchar(255) DEFAULT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`signal_user_id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- CREATE TEMPORARY TABLE signal_temp AS
--   SELECT *
--     FROM signal_daily;
--
--     INSERT INTO signal_temp
--   SELECT *
--     FROM signal_daily;
--
--     DROP TABLE signal_daily;
--
--     CREATE TABLE `signal_daily` (
--  `signal_id` int(11) NOT NULL AUTO_INCREMENT,
--  `symbol_id` int(11) NOT NULL,
--  `order_type` enum('1','2') NOT NULL COMMENT '1-Buy 2-Sell',
--  `price` decimal(10,5) NOT NULL,
--  `take_profit` decimal(10,5) NOT NULL,
--  `stop_loss` decimal(10,5) NOT NULL,
--  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--  `trigger_date` date NOT NULL,
--  `trigger_time` time NOT NULL,
--  `trend` enum('0','1') NOT NULL COMMENT '1-Bullish 0-Bearish',
--  `note` text,
--  `views` int(11) DEFAULT NULL,
--  `trigger_status` int(11) DEFAULT '0' COMMENT '0=pending 1=active 2=closed',
--  `entry_price` float DEFAULT NULL,
--  `entry_time` datetime DEFAULT NULL,
--  `exit_time` datetime DEFAULT NULL,
--  `pips` datetime DEFAULT NULL,
--  `exit_type` varchar(100) DEFAULT NULL,
--  PRIMARY KEY (`signal_id`),
--  UNIQUE KEY `signal_id` (`signal_id`)
-- )
--
-- INSERT INTO signal_daily
--   SELECT *
--     FROM signal_temp;