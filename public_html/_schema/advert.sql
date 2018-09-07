CREATE TABLE `advert_div` (
 `advert_id` int(10) NOT NULL AUTO_INCREMENT,
 `title` varchar(50) NOT NULL,
 `content` text NOT NULL,
 `status` enum('1','2') NOT NULL COMMENT '1- Display, 2- Hide',
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `created_by` varchar(10) NOT NULL,
 PRIMARY KEY (`advert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1


CREATE TABLE `unverified_campaign_mail_log` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email_flag` enum('1','2','3','4','5') NOT NULL COMMENT '1-1stMail, 2-2ndMail, 3-3rdMail, 4-4thMail, 5-5thMail',
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `email` varchar(100) NOT NULL,
 UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1
