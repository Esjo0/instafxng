CREATE TABLE `forum_schedule` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `main1` text NOT NULL,
 `sub1` text NOT NULL,
 `linkt` text NOT NULL,
 `link` varchar(100) NOT NULL,
 `image_path` varchar(100) NOT NULL,
 `main2` text NOT NULL,
 `sub2` text NOT NULL,
 `status` int(100) NOT NULL,
 `s_date` date NOT NULL,
 `admin` varchar(100) NOT NULL,
 `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1