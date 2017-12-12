
CREATE TABLE `article_comments` (
  `comment_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `article_id` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `reply_to` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(3) NOT NULL DEFAULT 'OFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
