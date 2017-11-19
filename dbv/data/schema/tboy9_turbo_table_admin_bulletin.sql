
CREATE TABLE `admin_bulletin` (
  `admin_bulletin_id` int(11) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `allowed_admin` text NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 - Published\n2 - Draft\n3 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
