
CREATE TABLE `push_notifications` (
  `notification_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipients` text NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `notification_type` enum('1') NOT NULL COMMENT '1 - project_messages'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
