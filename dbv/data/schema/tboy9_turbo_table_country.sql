
CREATE TABLE `country` (
  `country_id` int(11) NOT NULL,
  `country` varchar(30) NOT NULL,
  `capital` varchar(20) NOT NULL,
  `abbr` varchar(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
