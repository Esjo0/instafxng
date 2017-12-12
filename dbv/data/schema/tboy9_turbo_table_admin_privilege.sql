
CREATE TABLE `admin_privilege` (
  `admin_privilege_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `allowed_pages` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
