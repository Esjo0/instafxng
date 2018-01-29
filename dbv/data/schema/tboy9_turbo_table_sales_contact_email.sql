
CREATE TABLE `sales_contact_email` (
  `sales_contact_email_id` int(11) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
