
CREATE TABLE `sales_contact_comment` (
  `sales_contact_comment_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
