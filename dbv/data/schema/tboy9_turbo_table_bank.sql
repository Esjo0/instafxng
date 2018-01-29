
CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_alias` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
