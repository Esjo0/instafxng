
CREATE TABLE `signal_daily` (
  `signal_daily_id` int(11) NOT NULL,
  `symbol_id` int(11) NOT NULL,
  `order_type` varchar(4) NOT NULL,
  `price` varchar(10) NOT NULL,
  `take_profit` varchar(10) NOT NULL,
  `stop_loss` varchar(10) NOT NULL,
  `signal_date` date NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
