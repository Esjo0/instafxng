
CREATE TABLE `facility_request` (
  `facility_request_id` int(11) NOT NULL,
  `author` varchar(5) NOT NULL,
  `station_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Confirmed\n3 - Confirmation Declined\n4 - Approved\n5 - Approval Declined\n6 - Completed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
