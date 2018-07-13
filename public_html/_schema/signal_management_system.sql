CREATE TABLE `signal_daily` (
 `signal_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol_id` int(11) NOT NULL,
 `order_type` enum('1','2') NOT NULL COMMENT '1-Buy 2-Sell',
 `price` float NOT NULL,
 `take_profit` float NOT NULL,
 `stop_loss` float NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `trigger_date` date NOT NULL,
 `trigger_time` time NOT NULL,
 `trend` enum('0','1') NOT NULL COMMENT '1-Bullish 0-Bearish',
 `note` text,
 `views` int(11) DEFAULT '0',
 `trigger_status` enum('0','1','2') DEFAULT '0' COMMENT '0=pending 1=active 2=closed',
 `entry_price` float DEFAULT NULL,
 `entry_time` datetime DEFAULT NULL,
 `exit_time` datetime DEFAULT NULL,
 `pips` float DEFAULT NULL,
 PRIMARY KEY (`signal_id`),
 UNIQUE KEY `signal_id` (`signal_id`)
);
CREATE TABLE `signal_quotes` (
 `signal_quote_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol_id` int(11) NOT NULL,
 `bid` float NOT NULL,
 `ask` float NOT NULL,
 `created` datetime NOT NULL,
 PRIMARY KEY (`signal_quote_id`),
 UNIQUE KEY `signal_quote_id` (`signal_quote_id`)
);

CREATE TABLE `signal_symbol` (
 `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol` varchar(40) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`symbol_id`)
);

CREATE TABLE `signal_users` (
 `signal_user_id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `email` varchar(100) NOT NULL,
 `token` varchar(255) DEFAULT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`signal_user_id`),
 UNIQUE KEY `email` (`email`)
);