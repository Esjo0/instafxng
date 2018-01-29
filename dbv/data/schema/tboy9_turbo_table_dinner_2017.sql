
CREATE TABLE `dinner_2017` (
  `reservation_id` int(11) NOT NULL,
  `reservation_code` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `ticket_type` enum('0','1','2','3','4','5') NOT NULL COMMENT '0-Single   1-Double    2-VIP_Single 3-VIP_Double  4-Hired_help   5-Staff',
  `confirmation` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0-Pending  1-Maybe  2-Confirmed  3-Declined',
  `invite` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-Not Sent  1-Sent',
  `state_of_residence` varchar(255) NOT NULL,
  `comments` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attended` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-No   1-Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
