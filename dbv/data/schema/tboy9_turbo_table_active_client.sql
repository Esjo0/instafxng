
CREATE TABLE `active_client` (
  `active_client_id` int(11) NOT NULL,
  `clients` int(11) NOT NULL,
  `accounts` int(11) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
