
CREATE TABLE `signal_symbol` (
  `signal_symbol_id` int(11) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
