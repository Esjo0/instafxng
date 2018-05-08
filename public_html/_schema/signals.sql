CREATE TABLE `signal_intraday` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `currency_pair` varchar(100) NOT NULL,
 `buy_price` varchar(100) NOT NULL,
 `buy_price_tp` varchar(100) NOT NULL,
 `buy_price_sl` varchar(100) NOT NULL,
 `sell_price` varchar(100) NOT NULL,
 `sell_price_tp` varchar(100) NOT NULL,
 `sell_price_sl` varchar(100) NOT NULL,
 `signal_time` datetime(6) NOT NULL,
 `comment` text NOT NULL,
 `admin` varchar(100) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `status` int(11) NOT NULL,
 `views` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1