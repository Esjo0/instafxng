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
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1

CREATE TABLE `signal_quotes` (
 `signal_quote_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol_id` int(11) NOT NULL,
 `bid` float NOT NULL,
 `ask` float NOT NULL,
 `created` datetime NOT NULL,
 PRIMARY KEY (`signal_quote_id`),
 UNIQUE KEY `signal_quote_id` (`signal_quote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
signal_symbol 	CREATE TABLE `signal_symbol` (
 `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
 `symbol` varchar(40) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`symbol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1


 	CREATE TABLE `signal_users` (
 `signal_user_id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `email` varchar(100) NOT NULL,
 `token` varchar(255) DEFAULT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`signal_user_id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1