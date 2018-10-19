training_schedule_dates
CREATE TABLE `training_schedule_dates` (
 `schedule_id` int(10) NOT NULL AUTO_INCREMENT,
 `schedule_date` datetime NOT NULL,
 `schedule_type` enum('1','2') NOT NULL COMMENT '1- Public, 2-Private',
 `schedule_mode` enum('1','2') NOT NULL COMMENT '1- Online, 2-Offline',
 `no_of_students` int(100) NOT NULL,
 `location` int(10) NOT NULL COMMENT '1-Diamond Estate 2- Ajah',
 `admin` varchar(100) NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`schedule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1


training_schedule_students
CREATE TABLE `training_schedule_students` (
 `id` int(100) NOT NULL AUTO_INCREMENT,
 `schedule_id` int(100) NOT NULL,
 `user_code` varchar(100) NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `status` enum('0','1','2','3') NOT NULL COMMENT '0-scheduled, 1-completed, 2-rescheduled, 3-follow-up',
 `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1