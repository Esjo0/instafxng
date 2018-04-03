CREATE TABLE `easter_promo_entries` (
 `entry_id` int(11) NOT NULL AUTO_INCREMENT,
 `transaction_id` varchar(50) NOT NULL,
 `points` int(11) NOT NULL,
 `acc_no` varchar(50) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `completed` datetime DEFAULT NULL,
 PRIMARY KEY (`entry_id`),
 UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
easter_promo_participants 	CREATE TABLE `easter_promo_participants` (
 `participant_id` int(11) NOT NULL AUTO_INCREMENT,
 `acc_no` varchar(50) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`participant_id`),
 UNIQUE KEY `acc_no` (`acc_no`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8