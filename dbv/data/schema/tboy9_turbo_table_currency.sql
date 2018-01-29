
CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
