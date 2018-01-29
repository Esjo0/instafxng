CREATE TABLE `edu_lesson_rating` (
 `rating_id` int(11) NOT NULL AUTO_INCREMENT,
 `lesson_id` int(11) NOT NULL,
 `course_id` int(11) NOT NULL,
 `user_code` varchar(255) NOT NULL,
 `rating` enum('1','2','3','4','5') NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `comments` text,
 PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8