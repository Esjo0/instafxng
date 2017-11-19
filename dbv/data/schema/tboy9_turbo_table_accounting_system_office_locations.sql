
CREATE TABLE `accounting_system_office_locations` (
  `location_id` int(11) NOT NULL,
  `location` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
