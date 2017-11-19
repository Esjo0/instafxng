
CREATE TABLE `prospect_source` (
  `prospect_source_id` int(11) NOT NULL,
  `source_name` varchar(250) NOT NULL,
  `source_description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
