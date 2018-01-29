
CREATE TABLE `miss_tourism_lagos` (
  `miss_tourism_lagos_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `age` int(2) NOT NULL,
  `school` varchar(255) NOT NULL,
  `height` varchar(4) NOT NULL,
  `hobby` varchar(255) DEFAULT NULL,
  `fav_food` varchar(255) DEFAULT NULL,
  `ambition` text,
  `contest_id` int(2) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_count` int(2) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
