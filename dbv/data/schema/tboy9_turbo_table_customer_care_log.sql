
CREATE TABLE `customer_care_log` (
  `log_id` int(11) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `con_desc` text NOT NULL,
  `tag` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-PENDING   2-TREATED',
  `log_type` enum('1','2') NOT NULL COMMENT '1-CLIENT   2-PROSPECT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
