
CREATE TABLE `facility_request_comment` (
  `facility_request_comment_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `request_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
