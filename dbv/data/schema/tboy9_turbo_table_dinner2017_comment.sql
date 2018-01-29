
CREATE TABLE `dinner2017_comment` (
  `comment_id` int(11) NOT NULL,
  `reservation_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
