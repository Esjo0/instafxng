
CREATE TABLE `user_edu_deposits_comment` (
  `user_edu_deposits_comment_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
