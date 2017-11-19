
CREATE TABLE `admin_bulletin_comments` (
  `comment_id` int(11) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `bulletin_id` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `reply_to` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
