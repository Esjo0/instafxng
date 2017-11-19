
CREATE TABLE `prospect_biodata` (
  `prospect_biodata_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `other_names` varchar(200) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `prospect_source` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
