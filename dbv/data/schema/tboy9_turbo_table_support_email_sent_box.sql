
CREATE TABLE `support_email_sent_box` (
  `email_id` int(11) NOT NULL,
  `email_recipient` varchar(255) DEFAULT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `email_body` text NOT NULL,
  `email_attacment_url` varchar(255) DEFAULT NULL,
  `email_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_status` varchar(10) NOT NULL DEFAULT 'PENDING',
  `email_sender` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
