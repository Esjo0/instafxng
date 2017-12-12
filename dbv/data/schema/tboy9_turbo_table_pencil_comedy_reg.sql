
CREATE TABLE `pencil_comedy_reg` (
  `pencil_comedy_reg_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `client_comment` text NOT NULL,
  `state_of_residence` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
