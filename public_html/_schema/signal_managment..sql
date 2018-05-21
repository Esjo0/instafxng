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
 PRIMARY KEY (`signal_id`)
)

CREATE TABLE `signal_symbol` (
 `symbol_id` int(11) NOT NULL,
 `symbol` varchar(40) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`symbol_id`)
)