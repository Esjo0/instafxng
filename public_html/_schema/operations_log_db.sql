CREATE TABLE `operations_log` (
 `log_id` int(11) NOT NULL AUTO_INCREMENT,
 `transaction_id` varchar(100) DEFAULT NULL,
 `date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 `date_closed` varchar(50) DEFAULT NULL,
 `status` int(10) DEFAULT NULL COMMENT '0=Unresolved, 1=Resolved',
 `details` text NOT NULL,
 `admin` varchar(100) NOT NULL,
 PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1


CREATE TABLE `operations_log_comments` (
 `comment_id` int(11) NOT NULL AUTO_INCREMENT,
 `transaction_id` varchar(100) NOT NULL,
 `admin_code` varchar(100) NOT NULL,
 `comment` text NOT NULL,
 `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 UNIQUE KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1