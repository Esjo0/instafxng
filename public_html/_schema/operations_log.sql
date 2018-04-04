CREATE TABLE `operations_log` (
 `log_id` int(11) NOT NULL AUTO_INCREMENT,
 `client_name` varchar(100) DEFAULT NULL,
 `phone_no` varchar(100) DEFAULT NULL,
 `transaction_id` varchar(100) DEFAULT NULL,
 `transaction_type` varchar(100) DEFAULT NULL,
 `date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 `status` int(10) DEFAULT NULL COMMENT '0=Unresolved, 1=Resolved',
 `details` text NOT NULL,
 `admin` varchar(100) NOT NULL,
 PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1