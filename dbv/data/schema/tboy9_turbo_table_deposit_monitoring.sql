
CREATE TABLE `deposit_monitoring` (
  `deposit_monitoring_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
