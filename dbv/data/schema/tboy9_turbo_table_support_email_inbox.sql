
CREATE TABLE `support_email_inbox` (
  `email_id` int(11) NOT NULL,
  `email_sender` varchar(255) NOT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `email_body` text NOT NULL,
  `email_attacment_url` varchar(255) DEFAULT NULL,
  `email_created` varchar(255) NOT NULL,
  `email_admin_assigned` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
