
CREATE TABLE `article_visitors` (
  `visitor_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `block_status` varchar(3) NOT NULL DEFAULT 'OFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
