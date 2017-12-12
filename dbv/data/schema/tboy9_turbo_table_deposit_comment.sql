
CREATE TABLE `deposit_comment` (
  `deposit_comment_id` int(11) NOT NULL,
  `trans_id` varchar(15) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `comment` text NOT NULL COMMENT 'For comments entered by Admin\nAlso for system comments, e.g. Admin change to confirmed',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
