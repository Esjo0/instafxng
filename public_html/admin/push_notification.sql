DROP TABLE `push_notifications`;

CREATE TABLE `push_notifications` (
 `notification_id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `message` text NOT NULL,
 `recipients` text NOT NULL,
 `author` varchar(255) DEFAULT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `source_url` text,
 `status` enum('0','1') NOT NULL DEFAULT '0',
 PRIMARY KEY (`notification_id`)
)