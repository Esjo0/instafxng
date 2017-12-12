
CREATE TABLE `state` (
  `state_id` int(11) NOT NULL,
  `state` varchar(30) NOT NULL,
  `capital` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
