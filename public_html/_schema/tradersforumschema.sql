CREATE TABLE `forum_schedule` (
 `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
 `forum_title` text NOT NULL,
 `forum_date_details` text NOT NULL,
 `link_text` text NOT NULL,
 `link_url` varchar(100) NOT NULL,
 `image_path` varchar(100) NOT NULL,
 `share_thoughts_header` text NOT NULL,
 `share_thoughts_body` text NOT NULL,
 `status` int(100) NOT NULL,
 `scheduled_date` date NOT NULL,
 `admin` varchar(100) NOT NULL,
 `date_created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
 PRIMARY KEY (`schedule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1